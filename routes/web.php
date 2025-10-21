<?php

use App\Http\Controllers\SubjectController;
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
Route::controller(SubjectController::class)
    ->prefix('subjects') // URL ทั้งหมดจะขึ้นต้นด้วย /subjects
    ->name('subjects.')   // ชื่อ Route ทั้งหมดจะขึ้นต้นด้วย subjects.
    ->group(static function () {
        Route::get('/', action: 'list')->name('list');
        Route::get('/create', action: 'showCreateForm')->name('create-form');
        Route::post('/', action: 'create')->name('create');
        Route::get('/{subject}', action: 'view')->name('view');
        Route::get('/{subject}/edit', action: 'showUpdateForm')->name('show-update-form');
        Route::post('/{subject}/update', action: 'update')->name('update'); // or Route::put(...)
        Route::post('/{subject}/delete', action: 'delete')->name('delete'); // or Route::delete(...)

    });