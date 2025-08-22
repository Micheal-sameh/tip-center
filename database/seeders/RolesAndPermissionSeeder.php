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
        // users
        $users_delete = Permission::firstOrCreate(['name' => 'users_delete']);
        $users_update = Permission::firstOrCreate(['name' => 'users_update']);
        $users_create = Permission::firstOrCreate(['name' => 'users_create']);
        $users_view = Permission::firstOrCreate(['name' => 'users_view']);
        $users_resetPassword = Permission::firstOrCreate(['name' => 'users_resetPassword']);
        $users_changeStatus = Permission::firstOrCreate(['name' => 'users_changeStatus']);

        // professors
        $professors_delete = Permission::firstOrCreate(['name' => 'professors_delete']);
        $professors_update = Permission::firstOrCreate(['name' => 'professors_update']);
        $professors_create = Permission::firstOrCreate(['name' => 'professors_create']);
        $professors_view = Permission::firstOrCreate(['name' => 'professors_view']);
        $professors_changeStatus = Permission::firstOrCreate(['name' => 'professors_changeStatus']);

        // students
        $students_delete = Permission::firstOrCreate(['name' => 'students_delete']);
        $students_update = Permission::firstOrCreate(['name' => 'students_update']);
        $students_create = Permission::firstOrCreate(['name' => 'students_create']);
        $students_view = Permission::firstOrCreate(['name' => 'students_view']);
        $students_changeStatus = Permission::firstOrCreate(['name' => 'students_changeStatus']);
        // students
        $sessions_delete = Permission::firstOrCreate(['name' => 'sessions_delete']);
        $sessions_update = Permission::firstOrCreate(['name' => 'sessions_update']);
        $sessions_create = Permission::firstOrCreate(['name' => 'sessions_create']);
        $sessions_view = Permission::firstOrCreate(['name' => 'sessions_view']);
        $sessions_changeStatus = Permission::firstOrCreate(['name' => 'sessions_changeStatus']);

        // settings
        $settings_update = Permission::firstOrCreate(['name' => 'settings_update']);

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo([
            $users_delete,
            $users_update,
            $users_create,
            $users_view,
            $users_resetPassword,
            $users_changeStatus,

            $professors_delete,
            $professors_update,
            $professors_create,
            $professors_view,
            $professors_changeStatus,

            $students_delete,
            $students_update,
            $students_create,
            $students_view,
            $students_changeStatus,

            $sessions_delete,
            $sessions_update,
            $sessions_create,
            $sessions_view,
            $sessions_changeStatus,

            $settings_update,
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff']);
        $staff->givePermissionTo([
            $professors_view,

            $students_delete,
            $students_update,
            $students_create,
            $students_view,
            $students_changeStatus,

            $sessions_delete,
            $sessions_update,
            $sessions_create,
            $sessions_view,
            $sessions_changeStatus,
        ]);

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->givePermissionTo([
            $professors_view,
            $professors_update,
            $professors_create,
            $professors_view,
            $professors_changeStatus,

            $students_delete,
            $students_update,
            $students_create,
            $students_view,
            $students_changeStatus,

            $sessions_delete,
            $sessions_update,
            $sessions_create,
            $sessions_view,
            $sessions_changeStatus,
        ]);

        $user = Role::firstOrCreate(['name' => 'student']);
        $user->givePermissionTo([

        ]);
    }
}
