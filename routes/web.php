<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RedirectUrlController;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\ProfileColItem;

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

Route::prefix('/auth')
    ->name('auth.')
    ->controller(AuthController::class)
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/login', 'login')->name('login');
        Route::post('/logout', 'logout')->name('logout');
    });

Route::get('/', function () {
    return view('main.index');
})->middleware('auth');

Route::prefix('/dashboard')
    ->name('dashboard.')
    ->controller(DashboardController::class)
    ->middleware('auth')
    ->group(function() {
        Route::get('/', 'index')->name('index');
    });

Route::prefix('/links')
    ->name('link.')
    ->controller(LinkController::class)
    ->middleware('auth')
    ->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::post('/{id}', 'edit')->name('edit');
        Route::get('/download/{url}', 'download')->name('download');
        Route::post('/password/{id}', 'password')->name('password');
        Route::post('/time/{id}', 'time')->name('time');
        Route::delete('/time/{id}', 'removeTime');
        Route::delete('/password/{id}', 'removePassword');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

Route::prefix('/profile')
    ->name('profile.')
    ->controller(ProfileController::class)
    ->middleware('auth')
    ->group(function() {
        Route::get('/', 'index')->name('index');  
        Route::post('/edit/{id}', 'editProfile')->name('editProfile');
        Route::post('/edit/password/{id}', 'editPassword')->name('editPassword');         
    }); 

Route::prefix('/generate')
    ->name('api.')
    ->controller(ApiController::class)
    ->middleware('auth')
    ->group(function() {
        Route::post('/api/{id}', 'generate')->name('generate');
        Route::post('/api/regenerate/{id}', 'regenerate');
        Route::delete('/api/revoke/{id}', 'revoke');
    }); 

Route::prefix('/setting')
    ->name('setting.')
    ->controller(SettingController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'update');
    });

Route::prefix('/users')
    ->name('users.')
    ->controller(UserController::class)
    ->middleware('auth')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/datatables', 'userDataTable')->name('userDataTable');
        Route::get('/detail/{id}', 'detail')->name('detail');
        Route::post('/edit/{id}', 'editUser');
        Route::get('/delete/{id}', 'delete')->name('delete');
    });

Route::prefix('/activity')
    ->name('activity.')
    ->controller(ActivityController::class)
    ->middleware('auth') 
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/datatables', 'activityDataTable')->name('activityDataTable');
    });   

Route::prefix('/')
    ->name('redirect.')
    ->controller(RedirectUrlController::class)
    ->group(function () {
        Route::get('/{url}', 'redirect')->name('index');
        Route::get('/unlock/{id}', 'unlockPassword')->name('password');
    });