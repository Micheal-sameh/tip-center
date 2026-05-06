<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

/**
 * Creates (or resets) the "demo" tenant with realistic sample data.
 *
 * Run once:   php artisan db:seed --class=DemoTenantSeeder
 * Re-seed:    php artisan db:seed --class=DemoTenantSeeder  (idempotent)
 */
class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        $centralDomain = config('app.domain', 'tipcenter.test');
        $subdomain = 'demo';
        $domainFqdn = $subdomain.'.'.$centralDomain;

        // ── 1. Create (or retrieve) the demo tenant ──────────────────────
        $tenant = Tenant::find($subdomain);

        if (! $tenant) {
            $tenant = Tenant::create([
                'id' => $subdomain,
                'name' => 'Demo Center',
                'email' => 'demo@tipcenter.test',
                'plan' => 'professional',
                'is_demo' => true,
                'trial_ends_at' => now()->addYears(10),
            ]);

            $tenant->domains()->create(['domain' => $domainFqdn]);
        } else {
            // Ensure the is_demo flag is set
            $tenant->update(['is_demo' => true]);
        }

        // ── 2. Initialize the tenant context (switch to tenant DB) ───────
        tenancy()->initialize($tenant);

        // ── 3. Run all tenant migrations fresh ───────────────────────────
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Run the standard tenant migrations too
        Artisan::call('migrate', ['--force' => true]);

        // ── 4. Seed demo data ─────────────────────────────────────────────
        $this->seedSettings();
        $this->seedUsers();
        $this->seedProfessors();
        $this->seedStudents();

        tenancy()->end();

        $this->command->info("✅ Demo tenant ready at: http://{$domainFqdn}");
        $this->command->info('   Login: demo@tipcenter.test / password: demo1234');
    }

    // ── Settings ──────────────────────────────────────────────────────────
    private function seedSettings(): void
    {
        $settings = [
            ['name' => 'app_name',       'value' => 'Demo Center',    'type' => 'text'],
            ['name' => 'academic_year',  'value' => '25',             'type' => 'text'],
        ];

        foreach ($settings as $s) {
            \App\Models\Setting::firstOrCreate(['name' => $s['name']], $s);
        }
    }

    // ── Users ─────────────────────────────────────────────────────────────
    private function seedUsers(): void
    {
        if (! \Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'demo@tipcenter.test'],
            [
                'name' => 'Demo Admin',
                'password' => bcrypt('demo1234'),
                'status' => 'active',
            ]
        );

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }

    // ── Professors ────────────────────────────────────────────────────────
    private function seedProfessors(): void
    {
        $professors = [
            ['name' => 'Dr. Ahmed Hassan',   'phone' => '01001000001', 'subject' => 'Mathematics', 'school' => 'Cairo School', 'status' => 'active', 'type' => 'internal'],
            ['name' => 'Dr. Sara Mohamed',   'phone' => '01001000002', 'subject' => 'Physics',     'school' => 'Giza Academy', 'status' => 'active', 'type' => 'internal'],
            ['name' => 'Prof. Omar Khalid',  'phone' => '01001000003', 'subject' => 'Chemistry',   'school' => 'Alex Institute', 'status' => 'active', 'type' => 'external'],
            ['name' => 'Prof. Rania Ali',    'phone' => '01001000004', 'subject' => 'English',     'school' => 'Luxor School',  'status' => 'active', 'type' => 'internal'],
        ];

        foreach ($professors as $p) {
            \App\Models\Professor::firstOrCreate(['phone' => $p['phone']], $p);
        }
    }

    // ── Students ─────────────────────────────────────────────────────────
    private function seedStudents(): void
    {
        $students = [
            ['name' => 'Youssef Ibrahim',   'stage' => '11', 'phone' => '01101000001', 'parent_phone' => '01201000001'],
            ['name' => 'Nour Mahmoud',      'stage' => '12', 'phone' => '01101000002', 'parent_phone' => '01201000002'],
            ['name' => 'Hana Adel',         'stage' => '10', 'phone' => '01101000003', 'parent_phone' => '01201000003'],
            ['name' => 'Karim Tarek',       'stage' => '11', 'phone' => '01101000004', 'parent_phone' => '01201000004'],
            ['name' => 'Dina Samy',         'stage' => '12', 'phone' => '01101000005', 'parent_phone' => '01201000005'],
            ['name' => 'Hassan Nabil',      'stage' => '9',  'phone' => '01101000006', 'parent_phone' => '01201000006'],
            ['name' => 'Layla Mostafa',     'stage' => '10', 'phone' => '01101000007', 'parent_phone' => '01201000007'],
            ['name' => 'Mohamed Saber',     'stage' => '11', 'phone' => '01101000008', 'parent_phone' => '01201000008'],
        ];

        foreach ($students as $s) {
            \App\Models\Student::firstOrCreate(['phone' => $s['phone']], $s);
        }
    }
}
