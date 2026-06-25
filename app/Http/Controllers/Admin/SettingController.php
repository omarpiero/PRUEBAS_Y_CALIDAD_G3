<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:100'],
            'company_email' => ['nullable', 'email', 'max:100'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'company_description' => ['nullable', 'string', 'max:250'],
            'company_logo' => ['nullable', 'image', 'max:2048'], // 2MB max
            'payment_yape' => ['nullable', 'string', 'max:100'],
            'payment_plin' => ['nullable', 'string', 'max:100'],
            'payment_bank_name' => ['nullable', 'string', 'max:100'],
            'payment_bank_account' => ['nullable', 'string', 'max:100'],
            'payment_bank_cci' => ['nullable', 'string', 'max:100'],
            'payment_paypal' => ['nullable', 'string', 'max:250'],
            'max_upload_size_mb' => ['nullable', 'integer', 'min:1', 'max:500'],
            'max_video_size_mb' => ['nullable', 'integer', 'min:1', 'max:2000'],
        ]);

        $oldSettings = [];
        foreach (array_keys($validated) as $key) {
            $oldSettings[$key] = Setting::get($key);
        }

        foreach ($validated as $key => $value) {
            if ($key === 'company_logo') {
                if ($request->hasFile('company_logo')) {
                    $path = $request->file('company_logo')->store('settings', 'public');
                    // Delete old file if exists
                    $oldLogo = Setting::get('company_logo');
                    if ($oldLogo && file_exists(public_path('storage/' . $oldLogo))) {
                        @unlink(public_path('storage/' . $oldLogo));
                    }
                    Setting::set('company_logo', $path, 'image', 'empresa');
                }
                continue;
            }

            // Determine group
            $group = 'general';
            if (str_starts_with($key, 'company_')) {
                $group = 'empresa';
            } elseif (str_starts_with($key, 'payment_')) {
                $group = 'pagos';
            }

            Setting::set($key, $value ?? '', 'text', $group);
        }

        // Log audit
        \App\Services\AuditService::log(
            'update_global_settings',
            Setting::class,
            null,
            $oldSettings,
            $validated
        );

        // Also invalidate the dashboard cache
        \Illuminate\Support\Facades\Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Configuraciones actualizadas correctamente.');
    }
}
