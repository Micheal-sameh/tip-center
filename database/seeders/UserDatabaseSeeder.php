<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'super_admin',
            'email' => 'super_admin@tip.com',
            'phone' => '01234567890',
            'status' => '1',
            'birth_date' => Carbon::parse('01-01-2001'),
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');
    }
}
