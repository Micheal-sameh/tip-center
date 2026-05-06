<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central Web Routes  (marketing site, SaaS onboarding)
|--------------------------------------------------------------------------
| These routes only run on the central domain (e.g. center.crafando.com).
| Tenant-specific app routes live in routes/tenant.php and are served
| on each tenant's subdomain (e.g. demo.crafando.com).
|
*/

// Marketing / landing
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/about', [LandingController::class, 'about'])->name('landing.about');
Route::get('/demo', [LandingController::class, 'demo'])->name('landing.demo');

// Tenant self-service registration
Route::get('/register', [TenantRegistrationController::class, 'create'])->name('landing.register');
Route::post('/register', [TenantRegistrationController::class, 'store'])->name('landing.register.store');
Route::get('/register/success', [TenantRegistrationController::class, 'success'])->name('landing.register.success');

// ──────────────────────────────────────────────────────────────────────────────
// Super Admin Panel  (center.crafando.com/super-admin)
// ──────────────────────────────────────────────────────────────────────────────
Route::prefix('super-admin')->name('super-admin.')->group(function () {
    // Guest-only: login
    Route::get('/login', [SuperAdminController::class, 'loginPage'])->name('login');
    Route::post('/login', [SuperAdminController::class, 'login'])->name('login.post');

    // Authenticated: protected by super_admin middleware
    Route::middleware('super_admin')->group(function () {
        Route::post('/logout', [SuperAdminController::class, 'logout'])->name('logout');

        Route::get('/', [SuperAdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');
        Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])->name('tenants.create');
        Route::post('/tenants', [SuperAdminController::class, 'storeTenant'])->name('tenants.store');
        Route::get('/tenants/{id}', [SuperAdminController::class, 'showTenant'])->name('tenants.show');
        Route::patch('/tenants/{id}/toggle-demo', [SuperAdminController::class, 'toggleDemo'])->name('tenants.toggle-demo');
        Route::delete('/tenants/{id}', [SuperAdminController::class, 'deleteTenant'])->name('tenants.delete');
    });
});
