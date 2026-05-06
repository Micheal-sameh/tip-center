<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'admin@crafando.com')],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD', 'Admin@1234!')),
            ]
        );
    }
}
