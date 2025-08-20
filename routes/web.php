<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionStudentController;
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
    return auth()->check() ? to_route('attendances.index') : view('welcome.index');
});
Route::get('/professors/stage-row', function () {
    $index = request('index', 0);

    return view('professors.partials.stage_schedule_row', ['index' => $index]);
})->name('professors.stage-row');
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
            Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::get('/{id}/show', [UserController::class, 'show'])->name('users.show');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::get('/users/table', [UserController::class, 'tablePartial'])->name('users.table');
            Route::post('/', [UserController::class, 'store'])->name('users.store');
            Route::put('/{id}/status', [UserController::class, 'changeStatus'])->name('users.status');
            Route::put('/{id}/password', [UserController::class, 'updatePassword'])->name('users.updatePassword');
            Route::put('/{id}/reset', [UserController::class, 'resetPassword'])->name('users.resetPassword');
            Route::put('/{id}/profile-pic', [UserController::class, 'profilePic'])->name('users.pic_upload');
            Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/{id}', [UserController::class, 'delete'])->name('users.delete');
        });

        Route::prefix('professors')->group(function () {
            Route::get('/', [ProfessorController::class, 'index'])->name('professors.index');
            Route::get('/create', [ProfessorController::class, 'create'])->name('professors.create');
            Route::get('/dropdown', [ProfessorController::class, 'dropdown'])->name('professors.dropdown');
            Route::get('/{id}/show', [ProfessorController::class, 'show'])->name('professors.show');
            Route::get('/{id}/edit', [ProfessorController::class, 'edit'])->name('professors.edit');
            Route::post('/', [ProfessorController::class, 'store'])->name('professors.store');
            Route::put('/{id}/status', [ProfessorController::class, 'changeStatus'])->name('professors.status');
            Route::put('/{id}/settle', [ProfessorController::class, 'settle'])->name('professors.settle');
            Route::put('/{id}/profile-pic', [ProfessorController::class, 'profilePic'])->name('professors.profilePic');
            Route::put('/{id}', [ProfessorController::class, 'update'])->name('professors.update');
            Route::delete('/{id}', [ProfessorController::class, 'delete'])->name('professors.delete');
        });

        Route::prefix('sessions')->group(function () {
            Route::get('/', [SessionController::class, 'index'])->name('sessions.index');
            Route::get('/{professor_id}/create', [SessionController::class, 'create'])->name('sessions.create');
            Route::get('/{id}/show', [SessionController::class, 'show'])->name('sessions.show');
            Route::get('/{id}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
            Route::get('/{id}/students', [SessionController::class, 'students'])->name('sessions.students');
            Route::post('/', [SessionController::class, 'store'])->name('sessions.store');
            Route::put('/{id}/close', [SessionController::class, 'close'])->name('sessions.close');
            Route::put('/{id}/active', [SessionController::class, 'active'])->name('sessions.active');
            Route::put('/{id}', [SessionController::class, 'update'])->name('sessions.update');
            Route::delete('/{id}', [SessionController::class, 'delete'])->name('sessions.delete');
        });

        Route::prefix('students')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('students.index');
            Route::get('/search', [StudentController::class, 'search'])->name('students.search');
            Route::get('/create', [StudentController::class, 'create'])->name('students.create');
            Route::get('/{id}/show', [StudentController::class, 'show'])->name('students.show');
            Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
            Route::post('/', [StudentController::class, 'store'])->name('students.store');
            Route::put('/{id}/settle', [StudentController::class, 'settleDue'])->name('students.settle_due');
            // Route::put('/{id}/status', [StudentController::class, 'changeStatus'])->name('students.status');
            Route::put('/{id}/profile-pic', [StudentController::class, 'profilePic'])->name('students.profilePic');
            Route::put('/{id}', [StudentController::class, 'update'])->name('students.update');
            Route::delete('/{id}', [StudentController::class, 'delete'])->name('students.delete');
        });

        Route::prefix('session-students')->group(function () {
            Route::get('/', [SessionStudentController::class, 'index'])->name('attendances.index');
            Route::get('/create', [SessionStudentController::class, 'create'])->name('attendances.create');
            Route::get('/select-student', [SessionStudentController::class, 'selectStudent'])->name('attendance.select-student');
            Route::get('/{id}/show', [SessionStudentController::class, 'show'])->name('attendances.show');
            Route::get('/{id}/edit', [SessionStudentController::class, 'edit'])->name('attendances.edit');
            Route::post('/', [SessionStudentController::class, 'store'])->name('attendances.store');
            // Route::put('/{id}/status', [SessionStudentController::class, 'changeStatus'])->name('attendances.status');
            Route::put('/{id}', [SessionStudentController::class, 'update'])->name('attendances.update');
            Route::delete('/{id}', [SessionStudentController::class, 'delete'])->name('attendances.delete');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('reports.index');
            Route::get('/session', [ReportController::class, 'session'])->name('reports.session');
            Route::get('/students', [ReportController::class, 'student'])->name('reports.student');
            Route::get('/student-pdf', [ReportController::class, 'downloadStudentReport'])->name('reports.download.pdf');
            Route::get('/session-pdf', [ReportController::class, 'downloadSessionReport'])->name('reports.session.pdf');
        });

        Route::prefix('student-special-cases')->group(function () {
            Route::get('/create', [ReportController::class, 'create'])->name('student-special-cases.create');
            // Route::get('/session', [ReportController::class, 'session'])->name('reports.session');
            // Route::get('/students', [ReportController::class, 'student'])->name('reports.student');
            // Route::get('/student-pdf', [ReportController::class, 'downloadStudentReport'])->name('reports.download.pdf');
            // Route::get('/session-pdf', [ReportController::class, 'downloadSessionReport'])->name('reports.session.pdf');
        });

        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/', [SettingController::class, 'update'])->name('settings.update');
        });
    });

    Route::prefix('parents')->group(function () {
        Route::get('/', function () {
            return view('parents.index');
        })->name('parents.index');
        Route::get('/parent', [ReportController::class, 'parent'])->name('parents.student');
        Route::get('/student-pdf', [ReportController::class, 'downloadStudentReport'])->name('parents.download.pdf');
    });

});
