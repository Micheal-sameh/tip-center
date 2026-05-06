<?php

namespace App\Http\Controllers;

use App\Models\SuperAdmin;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    // ──────────────────────────────────────────────
    //  Auth
    // ──────────────────────────────────────────────

    public function loginPage()
    {
        if (session('super_admin_id')) {
            return redirect()->route('super-admin.dashboard');
        }

        return view('super-admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = SuperAdmin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return back()->withInput()->withErrors(['email' => 'Invalid credentials.']);
        }

        session([
            'super_admin_id' => $admin->id,
            'super_admin_name' => $admin->name,
            'super_admin_email' => $admin->email,
        ]);

        return redirect()->route('super-admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['super_admin_id', 'super_admin_name', 'super_admin_email']);

        return redirect()->route('super-admin.login');
    }

    // ──────────────────────────────────────────────
    //  Dashboard
    // ──────────────────────────────────────────────

    public function dashboard()
    {
        $stats = [
            'total' => Tenant::count(),
            'demo' => Tenant::where('is_demo', true)->count(),
            'active' => Tenant::where('is_demo', false)->count(),
        ];
        $recent = Tenant::with('domains')->latest()->take(8)->get();

        return view('super-admin.dashboard', compact('stats', 'recent'));
    }

    // ──────────────────────────────────────────────
    //  Tenants CRUD
    // ──────────────────────────────────────────────

    public function tenants(Request $request)
    {
        $tenants = Tenant::with('domains')
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%'))
            ->latest()
            ->paginate(20)
            ->appends($request->query());

        return view('super-admin.tenants', compact('tenants'));
    }

    public function showTenant($id)
    {
        $tenant = Tenant::with('domains')->findOrFail($id);
        $users = collect();

        try {
            tenancy()->initialize($tenant);
            $users = \App\Models\User::with('roles')->get();
            tenancy()->end();
        } catch (\Throwable $e) {
            tenancy()->end();
        }

        return view('super-admin.tenant-show', compact('tenant', 'users'));
    }

    public function createTenant()
    {
        return view('super-admin.tenant-create');
    }

    public function storeTenant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|alpha_dash|lowercase|max:63|unique:tenants,id',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $centralDomain = config('tenancy.central_domains')[0] ?? 'center.crafando.com';
        $parentDomain = implode('.', array_slice(explode('.', $centralDomain), 1)); // crafando.com

        $tenant = Tenant::create([
            'id' => $request->subdomain,
            'name' => $request->name,
            'email' => $request->email,
            'plan' => $request->plan ?? 'professional',
            'is_demo' => false,
        ]);

        $tenant->domains()->create(['domain' => $request->subdomain.'.'.$parentDomain]);

        // Seed admin user inside the tenant
        try {
            tenancy()->initialize($tenant);

            $user = \App\Models\User::create([
                'name' => 'Admin',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => \App\Enums\UserStatus::Active ?? 1,
            ]);

            if (class_exists(\Spatie\Permission\Models\Role::class)) {
                $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
                $user->assignRole($role);
            }

            tenancy()->end();
        } catch (\Throwable $e) {
            tenancy()->end();
        }

        return redirect()->route('super-admin.tenants')
            ->with('success', "Tenant \"{$tenant->name}\" created successfully.");
    }

    public function deleteTenant(Request $request, $id)
    {
        $tenant = Tenant::findOrFail($id);
        $name = $tenant->name;
        $tenant->delete();

        return redirect()->route('super-admin.tenants')
            ->with('success', "Tenant \"{$name}\" deleted.");
    }

    public function toggleDemo($id)
    {
        $tenant = Tenant::findOrFail($id);
        $tenant->update(['is_demo' => ! $tenant->is_demo]);

        return back()->with('success', 'Demo status updated.');
    }
}
