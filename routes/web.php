<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;

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

 Route::controller(LoginController::class)
            ->prefix('auth')
            ->group(static function (): void {
                // name this route to login by default setting.
                Route::get('/login', 'showLoginForm')->name('login');
                Route::get('/register', 'showRegisterForm')->name('register');
                Route::post('/register', 'register')->name('register.perform');
                Route::post('/login', 'authenticate')->name('authenticate');
                Route::post('/logout', 'logout')->name('logout');
            });

// Users listing and view (requires authenticated user)
// Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.adduser');
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
    Route::get('/students', [StudentController::class, 'index'])->name('students.list');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.destroy');
// });