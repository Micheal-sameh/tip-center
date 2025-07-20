<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users_delete = Permission::firstOrCreate(['name' => 'users_delete']);
        $users_update = Permission::firstOrCreate(['name' => 'users_update']);
        $users_create = Permission::firstOrCreate(['name' => 'users_create']);
        $users_view = Permission::firstOrCreate(['name' => 'users_view']);
        $users_resetPassword = Permission::firstOrCreate(['name' => 'users_resetPassword']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            $users_delete,
            $users_update,
            $users_create,
            $users_view,
            $users_resetPassword,
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo([

        ]);

        $user = Role::firstOrCreate(['name' => 'student']);
        $user->givePermissionTo([

        ]);

    }
}
