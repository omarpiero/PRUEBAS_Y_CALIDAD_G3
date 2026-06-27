<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('display_name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['integer', 'distinct', 'exists:roles,id'],
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $adminRoleId = $adminRole ? $adminRole->id : null;

        $rolesInput = collect($request->input('roles', []))
            ->map(fn ($roleId) => (int) $roleId)
            ->unique()
            ->values()
            ->all();

        $isAdminSelected = $adminRoleId && in_array((int) $adminRoleId, $rolesInput, true);

        $oldRoles = $user->roles->pluck('name')->toArray();
        $oldIsAdmin = (bool) $user->is_admin;

        if ($user->isAdmin() && ! $isAdminSelected && ! $this->hasAnotherAdmin($user)) {
            AuditService::log(
                'reject_last_admin_demotion',
                User::class,
                $user->id,
                ['roles' => $oldRoles, 'is_admin' => $oldIsAdmin],
                ['roles' => Role::whereIn('id', $rolesInput)->pluck('name')->all(), 'is_admin' => false]
            );

            return back()
                ->withErrors(['roles' => 'No puedes retirar el ultimo administrador activo del sistema.'])
                ->withInput();
        }

        DB::transaction(function () use ($user, $rolesInput, $isAdminSelected): void {
            $user->roles()->sync($rolesInput);
            $user->forceFill(['is_admin' => (bool) $isAdminSelected])->save();
        });

        // Audit Log
        AuditService::log(
            'update_user_roles',
            User::class,
            $user->id,
            ['roles' => $oldRoles, 'is_admin' => $oldIsAdmin],
            ['roles' => $user->fresh()->roles->pluck('name')->toArray(), 'is_admin' => $isAdminSelected]
        );

        // Invalidate dashboard stats since role distribution might have changed
        Cache::forget('admin_dashboard_stats');

        return redirect()->route('admin.users')
            ->with('status', "Roles de {$user->name} actualizados correctamente.");
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        // Keep for routes compatibility, but redirect to edit roles
        return redirect()->route('admin.users.edit', $user);
    }

    private function hasAnotherAdmin(User $user): bool
    {
        return User::query()
            ->whereKeyNot($user->id)
            ->where(function ($query): void {
                $query->where('is_admin', true)
                    ->orWhereHas('roles', fn ($roles) => $roles->where('name', 'admin'));
            })
            ->exists();
    }
}
