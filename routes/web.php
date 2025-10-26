<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

// Public account creation page (Thai: สร้างบัญชี)
Route::get('/create', function () {
    return view('users.create');
});

Route::post('/create', [\App\Http\Controllers\RegistrationController::class, 'store']);

Route::get('/home', function () {
    return view('indexs.home');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Users listing and view (requires authenticated user)
// Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'view'])->name('users.view');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Teachers
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.list');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{id}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{id}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{id}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{id}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    //Subject

// Group route สำหรับ subject
Route::controller(SubjectController::class)
    ->prefix('subjects')
    ->name('subjects.')
    ->group(function () {
        Route::get('/', 'list')->name('list');            // Route /subjects
        Route::get('/{subject}', 'view')->name('view');    // Route /subjects/{subject}
    });

    // Students
    Route::controller(StudentController::class)
    ->prefix('/students')
    ->name('students.')
    ->group(static function (): void {
    Route::get('', 'index')->name('list');
    Route::get('/create', 'create')->name('create');
    Route::post('','store')->name('store');
    Route::prefix('/{id}')->group(static function (): void {
        Route::get('', 'show')->name('show');
        Route::get('/edit', 'edit')->name('edit');
        Route::put('/update', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
        Route::get('/addsubform', 'showaddsubform')->name('add-subject-form');
        Route::post('/addsub', 'addsub')->name('add-subject');
        Route::Get('/schedule', 'schedule')->name('schedule');
    });
});
    
// });

// Temporary debug route: shows whether student(s)/teacher(s) tables exist and row counts
Route::get('/debug-tables', function () {
    $tables = [
        'students', 'student',
        'teachers', 'teacher',
        'users'
    ];

    $result = [];
    foreach ($tables as $t) {
        $exists = Schema::hasTable($t);
        $count = null;
        if ($exists) {
            try {
                $count = DB::table($t)->count();
            } catch (\Throwable $e) {
                $count = 'error: ' . $e->getMessage();
            }
        }
        $result[$t] = ['exists' => $exists, 'count' => $count];
    }

    return response()->json($result);
});