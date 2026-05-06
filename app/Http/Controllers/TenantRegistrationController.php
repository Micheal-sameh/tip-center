<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TenantRegistrationController extends Controller
{
    public function create()
    {
        return view('landing.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'center_name' => ['required', 'string', 'max:100'],
            'subdomain' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-z0-9\-]+$/',
                Rule::unique('tenants', 'id'),
            ],
            'email' => ['required', 'email', 'max:150'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $subdomain = strtolower($request->subdomain);
        $centralDomain = config('app.domain', 'tipcenter.test');

        /** @var Tenant $tenant */
        $tenant = Tenant::create([
            'id' => $subdomain,
            'name' => $request->center_name,
            'email' => $request->email,
            'plan' => 'trial',
            'is_demo' => false,
            'trial_ends_at' => now()->addDays(14),
        ]);

        $tenant->domains()->create([
            'domain' => $subdomain.'.'.$centralDomain,
        ]);

        // Seed the first admin user inside the new tenant's DB
        tenancy()->initialize($tenant);

        \App\Models\User::create([
            'name' => $request->center_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ])->assignRole('admin');

        tenancy()->end();

        $tenantUrl = 'http://'.$subdomain.'.'.$centralDomain;

        return redirect()->route('landing.register.success')
            ->with('tenant_url', $tenantUrl)
            ->with('center_name', $request->center_name);
    }

    public function success()
    {
        if (! session()->has('tenant_url')) {
            return redirect()->route('landing.index');
        }

        return view('landing.register-success', [
            'tenant_url' => session('tenant_url'),
            'center_name' => session('center_name'),
        ]);
    }
}
