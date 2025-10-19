<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
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
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'list'])->name('users.list');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}', [UserController::class, 'view'])->name('users.view');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
});