<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\RedirectUrlController;
use App\Http\Controllers\SettingController;
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
    return view('main.index');
});

Route::prefix('/dashboard')
    ->name('dashboard.')
    ->controller(DashboardController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
    });

Route::prefix('/links')
    ->name('link.')
    ->controller(LinkController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{id}', 'edit')->name('edit');
        Route::get('/download/{url}', 'download')->name('download');
        Route::post('/password/{id}', 'password')->name('password');
        Route::delete('/password/{id}', 'removePassword');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

Route::prefix('/setting')
    ->name('setting.')
    ->controller(SettingController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });

Route::prefix('/')
    ->name('redirect.')
    ->controller(RedirectUrlController::class)
    ->group(function () {
        Route::get('/{url}', 'redirect')->name('index');
        Route::get('/unlock/{id}', 'unlockPassword')->name('password');
    });