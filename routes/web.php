<?php

use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(SubjectController::class)
    ->prefix('/subjects')
    ->name('subjects.')
    ->group(static function (): void {
        Route::get('', 'list')->name('list');
        Route::get('/{subject}', 'view')->name('view');
    });