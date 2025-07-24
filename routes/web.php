<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => ['setlocale']], function () {
    // Language change routes (optional, if you want to switch languages via URL)
    Route::get('/lang/{lang}', function ($lang) {
        // You can redirect to a page after changing the language
        session(['lang' => $lang]);  // Store the language in session

        return redirect()->back();
    });
    Route::prefix('auth')->group(function () {
        Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
        Route::post('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth'])->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('users.index');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::get('/{id}/show', [UserController::class, 'show'])->name('users.show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::get('/users/table', [UserController::class, 'tablePartial'])->name('users.table');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::put('/{id}/status', [UserController::class, 'changeStatus'])->name('users.status');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{id}', [UserController::class, 'delete'])->name('users.delete');
        });

        Route::prefix('professors')->group(function () {
            Route::get('/', [ProfessorController::class, 'index'])->name('professors.index');
            Route::get('/create', [ProfessorController::class, 'create'])->name('professors.create');
            Route::get('/{id}/show', [ProfessorController::class, 'show'])->name('professors.show');
            Route::get('/{id}/edit', [ProfessorController::class, 'edit'])->name('professors.edit');
            Route::post('/', [ProfessorController::class, 'store'])->name('professors.store');
            Route::put('/{id}/status', [ProfessorController::class, 'changeStatus'])->name('professors.status');
            Route::put('/{id}', [ProfessorController::class, 'update'])->name('professors.update');
            Route::delete('/{id}', [ProfessorController::class, 'delete'])->name('professors.delete');
        });

        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('/create', [StudentController::class, 'create'])->name('students.create');
            Route::get('/{id}/show', [StudentController::class, 'show'])->name('students.show');
            Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::post('/', [StudentController::class, 'store'])->name('students.store');
            // Route::put('/{id}/status', [StudentController::class, 'changeStatus'])->name('students.status');
            Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('/{id}', [StudentController::class, 'delete'])->name('students.delete');
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/', [SettingController::class, 'update'])->name('settings.update');
        });
    });
});
