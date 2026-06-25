<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use App\Models\Sale;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Cache heavy analytical queries for 10 minutes
        $analytics = Cache::remember('admin_dashboard_stats', 600, function () {
            // Courses KPIs
            $totalCourses = Course::count();
            $activeCourses = Course::where('status', 'publicado')->count();
            $inactiveCourses = Course::where('status', 'borrador')->count();

            // Students KPIs (role based or legacy is_admin)
            $totalStudents = User::whereHas('roles', function ($q) {
                $q->where('name', 'estudiante');
            })->orWhere(function ($q) {
                $q->whereDoesntHave('roles')->where('is_admin', false);
            })->count();

            $newStudentsMonth = User::where('created_at', '>=', now()->startOfMonth())
                ->where(function ($q) {
                    $q->whereHas('roles', function ($qr) {
                        $qr->where('name', 'estudiante');
                    })->orWhere(function ($qr) {
                        $qr->whereDoesntHave('roles')->where('is_admin', false);
                    });
                })->count();

            $totalInstructors = User::whereHas('roles', function ($q) {
                $q->where('name', 'instructor');
            })->count();

            $totalAdmins = User::where('is_admin', true)
                ->orWhereHas('roles', function ($q) {
                    $q->where('name', 'admin');
                })->count();

            // Sales & Revenue KPIs (only for paid sales)
            $salesThisMonth = Sale::paid()->thisMonth()->sum('total');
            $salesTotalHistoric = Sale::paid()->sum('total');
            $paidSalesCount = Sale::paid()->count();
            $averageTicket = $paidSalesCount > 0 ? ($salesTotalHistoric / $paidSalesCount) : 0;

            // Completion Rate
            $totalEnrollmentsCount = Enrollment::count();
            $completedEnrollmentsCount = Enrollment::where('status', 'completado')->count();
            $completionRate = $totalEnrollmentsCount > 0 
                ? ($completedEnrollmentsCount / $totalEnrollmentsCount) * 100 
                : 0;

            // Course selling details
            $bestSellingCourse = Course::withCount(['saleItems' => function ($q) {
                $q->whereHas('sale', function ($qs) {
                    $qs->paid();
                });
            }])->orderByDesc('sale_items_count')->first();

            $lowestPerformingCourse = Course::published()->withCount(['saleItems' => function ($q) {
                $q->whereHas('sale', function ($qs) {
                    $qs->paid();
                });
            }])->orderBy('sale_items_count')->first();

            // Generate Monthly Sales and Enrollments for the last 12 months
            $monthlySalesData = [];
            $monthlyEnrollmentsData = [];
            $monthLabels = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $year = $date->year;
                $month = $date->month;
                $monthName = $date->format('M Y');

                $monthLabels[] = $monthName;

                // Sum of paid sales
                $salesSum = Sale::paid()
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->sum('total');
                $monthlySalesData[] = (float)$salesSum;

                // Count of enrollments
                $enrollmentsCount = Enrollment::whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->count();
                $monthlyEnrollmentsData[] = (int)$enrollmentsCount;
            }

            // Top 5 selling courses
            $topCourses = Course::withCount(['saleItems' => function ($q) {
                $q->whereHas('sale', function ($qs) {
                    $qs->paid();
                });
            }])
            ->orderByDesc('sale_items_count')
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'name' => $course->name,
                    'sales_count' => $course->sale_items_count,
                ];
            })->toArray();

            return [
                'total_courses' => $totalCourses,
                'active_courses' => $activeCourses,
                'inactive_courses' => $inactiveCourses,
                'total_students' => $totalStudents,
                'new_students_month' => $newStudentsMonth,
                'total_instructors' => $totalInstructors,
                'total_admins' => $totalAdmins,
                'sales_this_month' => $salesThisMonth,
                'sales_total_historic' => $salesTotalHistoric,
                'average_ticket' => $averageTicket,
                'completion_rate' => $completionRate,
                'best_selling_course' => $bestSellingCourse ? $bestSellingCourse->name : 'Ninguno',
                'best_selling_sales' => $bestSellingCourse ? $bestSellingCourse->sale_items_count : 0,
                'lowest_performing_course' => $lowestPerformingCourse ? $lowestPerformingCourse->name : 'Ninguno',
                'lowest_performing_sales' => $lowestPerformingCourse ? $lowestPerformingCourse->sale_items_count : 0,
                'month_labels' => $monthLabels,
                'monthly_sales_data' => $monthlySalesData,
                'monthly_enrollments_data' => $monthlyEnrollmentsData,
                'top_courses' => $topCourses,
            ];
        });

        // Non-cached real-time stats (e.g. recent lists and unread count)
        $stats = array_merge($analytics, [
            'total_users'       => User::count(),
            'admin_users'       => User::where('is_admin', true)->count(),
            'new_this_week'     => User::where('created_at', '>=', now()->subDays(7))->count(),
            'new_this_month'    => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'recent_users'      => User::latest()->take(8)->get(),
            'total_contacts'    => Contact::count(),
            'unread_contacts'   => Contact::where('leido', false)->count(),
            'recent_contacts'   => Contact::latest()->take(6)->get(),
            'total_enrollments' => Enrollment::count(),
            'recent_enrollments'=> Enrollment::with(['user', 'course'])->latest()->take(6)->get(),
        ]);

        return view('admin.dashboard', compact('stats'));
    }
}
