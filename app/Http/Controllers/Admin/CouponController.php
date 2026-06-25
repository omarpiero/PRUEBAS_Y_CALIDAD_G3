<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'string', 'in:porcentaje,monto_fijo'],
            'value' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ], [
            'code.required' => 'El código del cupón es obligatorio.',
            'code.unique' => 'Este código de cupón ya está registrado.',
            'type.in' => 'El tipo de descuento seleccionado no es válido.',
            'value.required' => 'El valor de descuento es obligatorio.',
            'value.numeric' => 'El valor debe ser un número.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->has('is_active');
        $data['times_used'] = 0;

        $coupon = Coupon::create($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'create_coupon',
            'entity_type' => Coupon::class,
            'entity_id' => $coupon->id,
            'new_values' => $coupon->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Cupón {$coupon->code} creado con éxito.");
    }

    public function show(Coupon $coupon)
    {
        return redirect()->route('admin.coupons.edit', $coupon);
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in database.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $coupon->id],
            'type' => ['required', 'string', 'in:porcentaje,monto_fijo'],
            'value' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
        ], [
            'code.required' => 'El código del cupón es obligatorio.',
            'code.unique' => 'Este código de cupón ya está registrado.',
            'type.in' => 'El tipo de descuento seleccionado no es válido.',
            'value.required' => 'El valor de descuento es obligatorio.',
            'value.numeric' => 'El valor debe ser un número.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ]);

        $oldValues = $coupon->toArray();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_coupon',
            'entity_type' => Coupon::class,
            'entity_id' => $coupon->id,
            'old_values' => $oldValues,
            'new_values' => $coupon->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Cupón {$coupon->code} actualizado con éxito.");
    }

    /**
     * Remove the specified coupon from database.
     */
    public function destroy(Request $request, Coupon $coupon)
    {
        $oldValues = $coupon->toArray();
        $coupon->delete();

        // Audit Log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete_coupon',
            'entity_type' => Coupon::class,
            'entity_id' => $coupon->id,
            'old_values' => $oldValues,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', "Cupón eliminado exitosamente.");
    }
}
