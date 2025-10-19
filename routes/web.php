<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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