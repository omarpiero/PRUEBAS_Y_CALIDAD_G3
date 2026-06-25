<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    /**
     * Display a listing of sales.
     */
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'coupon'])->latest();

        // Filter by user name/email or sale ID
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhere('id', $search);
            });
        }

        // Filter by payment status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->input('status'));
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('admin.sales.index', compact('sales'));
    }

    /**
     * Display details of a specific sale.
     */
    public function show(Sale $sale)
    {
        $sale->load(['user', 'coupon', 'items.course']);

        return view('admin.sales.show', compact('sale'));
    }
}
