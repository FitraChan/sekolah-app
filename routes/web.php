<?php


use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\Home;

use App\Http\Controllers\RoleController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', [AuthController::class, 'FormLogin'])->name('form-login');

Route::post('/login', [AuthController::class, 'Login'])->name('login');
Route::get('/logout', [AuthController::class, 'Logout'])->name('logout');


Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [Home::class, 'index'])
        ->name('admin');

    Route::get('/role', [RoleController::class, 'index'])
    ->name('role.index');

    Route::get('/role/data', [RoleController::class, 'data'])
    ->name('role.data');

    Route::post('/role/store', [RoleController::class, 'storeRole'])->name('role.store');

    Route::post('/role/update/{id}', [RoleController::class, 'update'])
        ->name('role.update');
});


