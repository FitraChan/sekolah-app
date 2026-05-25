<?php


use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\Home;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

// use App\Http\Controllers\GelombangController;

use App\Http\Controllers\pendaftaran\GelombangController;
use App\Http\Controllers\pendaftaran\CalonSiswaController;
use App\Http\Controllers\pendaftaran\SetKelasController;
use App\Http\Controllers\pendaftaran\BroadcastController;




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
    Route::get('/role/data-role', [RoleController::class, 'dataRole'])->name('role.dataRole');
    Route::post('/role/storeUser', [RoleController::class, 'storeUser'])->name('role.store');
    Route::get('/role/{id}/editUser', [RoleController::class, 'editUser']);
    Route::post('/role/updateUser/{id}', [RoleController::class, 'updateUser']);
    Route::delete('/role/deleteRole/{id}', [RoleController::class, 'deleteRole'])->name('role.deleteRole');


    Route::get('/gelombang', [GelombangController::class, 'index'])->name('gelombang.index');
    Route::get('/gelombang/data', [GelombangController::class, 'data'])->name('gelombang.data');
    Route::post('/gelombang/store', [GelombangController::class, 'store'])->name('gelombang.store');
    Route::post('/gelombang/update/{id}', [GelombangController::class, 'update'])->name('gelombang.update');
    Route::delete('/gelombang/delete/{id}', [GelombangController::class, 'destroy'])->name('gelombang.delete');

    Route::get('/calon-siswa', [CalonSiswaController::class, 'index'])
        ->name('calon-siswa.index');
    Route::get('/calon-siswa/data', [CalonSiswaController::class, 'data'])
        ->name('calon-siswa.data');
    Route::post('/calon-siswa/store', [CalonSiswaController::class, 'store'])->name('calon-siswa.store');
    //  Route::post('/calon-siswa/update/{id}', [CalonSiswaController::class, 'update'])->name('calon-siswa.update');
    Route::get('/calon-siswa/edit/{id}', [CalonSiswaController::class, 'edit'])
        ->name('calon-siswa.edit');

    Route::get('/calon-siswa/create', [CalonSiswaController::class, 'create'])
        ->name('calon-siswa.create');



    Route::delete('/calon-siswa/delete/{id}', [CalonSiswaController::class, 'destroy'])->name('calon-siswa.delete');


    Route::post(
        '/calon-siswa/update/updateRegistrasiSiswa/{id?}',
        [CalonSiswaController::class, 'updateRegistrasiSiswa']
    )
        ->name('calon-siswa.update.updateRegistrasiSiswa');



    Route::post(
        '/calon-siswa/update/orang-tua/{id}',
        [CalonSiswaController::class, 'updateOrangTua']
    )
        ->name('calon-siswa.update.orangtua');

    Route::post(
        '/calon-siswa/update/registrasi/{id}',
        [CalonSiswaController::class, 'updateRegistrasi']
    )
        ->name('calon-siswa.update.registrasi');

    Route::post(
        '/calon-siswa/update-status/{id}',
        [CalonSiswaController::class, 'updateStatus']
    )->name('calon-siswa.update-status');

    // halaman
    Route::get('/set-kelas', [
        SetKelasController::class,
        'index'
    ])->name('set-kelas.index');

    // data tabulator
    Route::get('/data', [
        SetKelasController::class,
        'data'
    ])->name('set-kelas.data');

    // update kelas
    Route::post('/set-kelas/updateKelas/{id}', [
        SetKelasController::class,
        'updateKelas'
    ])->name('set-kelas.update');


    Route::get('/rekapKelas', [
        SetKelasController::class,
        'rekapKelas'
    ])->name('rekapKelas');

    Route::get('/daftarSiswa', [
        CalonSiswaController::class,
        'daftarSiswa'
    ])->name('daftarSiswa');

    Route::prefix('broadcast')->group(function () {

        Route::get('/', [
            BroadcastController::class,
            'index'
        ])->name('broadcast.index');

        Route::get('/data', [
            BroadcastController::class,
            'data'
        ])->name('broadcast.data');

        Route::post('/store', [
            BroadcastController::class,
            'store'
        ])->name('broadcast.store');

        Route::post('/update/{id}', [
            BroadcastController::class,
            'update'
        ])->name('broadcast.update');

        Route::delete('/delete/{id}', [
            BroadcastController::class,
            'delete'
        ])->name('broadcast.delete');
    });
});
