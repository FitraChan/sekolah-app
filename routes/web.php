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
use App\Http\Controllers\keuangan\KatItemBayarController;
use App\Http\Controllers\keuangan\KatPeriodeBayarController;
use App\Http\Controllers\keuangan\ItemBayarController;
use App\Http\Controllers\keuangan\TemplateBayarController;
use App\Http\Controllers\keuangan\BayarController;





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


Route::get('registerSiswa', [AuthController::class, 'registerSiswa'])->name('registerSiswa');

Route::post('/cekregister', [AuthController::class, 'cekregister'])->name('cekregister');




Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/role', [RoleController::class, 'index'])
        ->name('role.index');
    Route::get('/role/data', [RoleController::class, 'data'])
        ->name('role.data');
    Route::post('/role/store', [RoleController::class, 'storeRole'])->name('role.store');
    Route::get('/role/data-role', [RoleController::class, 'dataRole'])->name('role.dataRole');
    Route::post('/role/storeUser', [RoleController::class, 'storeUser'])->name('role.store');
    Route::get('/role/editUser/{id}', [RoleController::class, 'editUser'])->name('role.editUser');
    Route::post('/role/updateUser/{id}', [RoleController::class, 'updateUser'])->name('role.updateUser');
    Route::delete('/role/deleteRole/{id}', [RoleController::class, 'deleteRole'])->name('role.deleteRole');

    Route::get('kat-item-bayar', [KatItemBayarController::class, 'index'])
        ->name('kat-item-bayar.index');
    Route::get('kat-item-bayar/data', [KatItemBayarController::class, 'data'])
        ->name('kat-item-bayar.data');
    Route::post('kat-item-bayar/store', [KatItemBayarController::class, 'store'])
        ->name('kat-item-bayar.store');
    Route::get('kat-item-bayar/edit/{id}', [KatItemBayarController::class, 'edit'])
        ->name('kat-item-bayar.edit');
    Route::post('kat-item-bayar/update/{id}', [KatItemBayarController::class, 'update'])
        ->name('kat-item-bayar.update');
    Route::delete('kat-item-bayar/delete/{id}', [KatItemBayarController::class, 'destroy'])
        ->name('kat-item-bayar.delete');
    Route::get(
        '/kat-periode-bayar',
        [KatPeriodeBayarController::class, 'index']
    )->name('kat-periode-bayar.index');

    Route::get(
        '/kat-periode-bayar/data',
        [KatPeriodeBayarController::class, 'data']
    )->name('kat-periode-bayar.data');

    Route::post(
        '/kat-periode-bayar/store',
        [KatPeriodeBayarController::class, 'store']
    )->name('kat-periode-bayar.store');

    Route::post(
        '/kat-periode-bayar/update/{id}',
        [KatPeriodeBayarController::class, 'update']
    )->name('kat-periode-bayar.update');

    Route::delete(
        '/kat-periode-bayar/delete/{id}',
        [KatPeriodeBayarController::class, 'destroy']
    )->name('kat-periode-bayar.delete');

    Route::get(
        '/item-bayar',
        [ItemBayarController::class, 'index']
    )->name('item-bayar.index');

    Route::get(
        '/item-bayar/data',
        [ItemBayarController::class, 'data']
    )->name('item-bayar.data');

    Route::post(
        '/item-bayar/store',
        [ItemBayarController::class, 'store']
    )->name('item-bayar.store');

    Route::post(
        '/item-bayar/update/{id}',
        [ItemBayarController::class, 'update']
    )->name('item-bayar.update');

    Route::delete(
        '/item-bayar/delete/{id}',
        [ItemBayarController::class, 'destroy']
    )->name('item-bayar.delete');

    Route::get(
        '/template-bayar',
        [TemplateBayarController::class, 'index']
    )->name('template-bayar.index');

    Route::get(
        '/template-bayar/data',
        [TemplateBayarController::class, 'data']
    )->name('template-bayar.data');

    Route::post(
        '/template-bayar/store',
        [TemplateBayarController::class, 'store']
    )->name('template-bayar.store');

    Route::post(
        '/template-bayar/update/{id}',
        [TemplateBayarController::class, 'update']
    )->name('template-bayar.update');

    Route::delete(
        '/template-bayar/delete/{id}',
        [TemplateBayarController::class, 'delete']
    )->name('template-bayar.delete');

    Route::get(
        '/template-bayar/detail/{id}',
        [TemplateBayarController::class, 'detail']
    )->name('template-bayar.detail');

    Route::post(
        '/template-bayar/set-default/{id}',
        [TemplateBayarController::class, 'setDefault']
    )->name('template-bayar.set-default');

    Route::post(
        '/template-bayar-detail/storeDetail',
        [TemplateBayarController::class, 'storeDetail']
    )->name('template-bayar-detail.storeDetail');


    Route::post(
        '/template-bayar-detail/updateDetail/{id}',
        [TemplateBayarController::class, 'updateDetail']
    )->name('template-bayar-detail.updateDetail');

    Route::delete(
        '/template-bayar-detail/deleteDetail/{id}',
        [TemplateBayarController::class, 'deleteDetail']
    )->name('template-bayar-detail.deleteDetail');


    Route::get('/bayar', [BayarController::class, 'index'])
        ->name('bayar.index');

    // Data siswa untuk DataTables/Ajax
    Route::get('/bayar/data', [BayarController::class, 'data'])
        ->name('bayar.data');

    // Detail pembayaran berdasarkan NIPD
    Route::get('/bayar/detail/{nipd}', [BayarController::class, 'detail'])
        ->name('bayar.detail');

    Route::get('/bayar/detailBayar/{nipd}', [BayarController::class, 'detailBayar'])
        ->name('bayar.detailBayar');

    Route::post('/bayar/setDefBulan', [BayarController::class, 'setDefBulan'])
        ->name('bayar.setDefBulan');

        Route::post('/bayar/set-lunas/{id}', [BayarController::class, 'setLunas'])
    ->name('bayar.set-lunas');
});


Route::middleware(['auth', 'role:admin|calon'])->group(function () {

    Route::post(
        '/calon-siswa/update/updateRegistrasiSiswa/{id?}',
        [CalonSiswaController::class, 'updateRegistrasiSiswa']
    )->name('calon-siswa.update.updateRegistrasiSiswa');
});


Route::middleware(['auth', 'role:admin|Akademik'])->group(function () {

    Route::get('/admin', [Home::class, 'index'])
        ->name('admin');

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
        '/calon-siswa/update/orang-tua/{id}',
        [CalonSiswaController::class, 'updateOrangTua']
    )->name('calon-siswa.update.orangtua');
    Route::post(
        '/calon-siswa/update/registrasi/{id}',
        [CalonSiswaController::class, 'updateRegistrasi']
    )->name('calon-siswa.update.registrasi');
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
    Route::get('set-kelas/data', [
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
    // Route::prefix('broadcast')->group(function () {
    Route::get('broadcast', [
        BroadcastController::class,
        'index'
    ])->name('broadcast.index');
    Route::get('broadcast/data', [
        BroadcastController::class,
        'data'
    ])->name('broadcast.data');
    Route::post('broadcast/store', [
        BroadcastController::class,
        'store'
    ])->name('broadcast.store');
    Route::post('broadcast/update/{id}', [
        BroadcastController::class,
        'update'
    ])->name('broadcast.update');
    Route::delete('broadcast/delete/{id}', [
        BroadcastController::class,
        'delete'
    ])->name('broadcast.delete');
    Route::post('broadcast/kirimSemua', [BroadcastController::class, 'kirimSemua']);
    Route::post('calon-siswa/upload/{id}', [CalonSiswaController::class, 'updateUpload'])->name('calon-siswa.update.upload');
});


Route::middleware(['auth', 'role:calon'])->group(function () {

    Route::get(
        '/calon-siswa/profil',
        [CalonSiswaController::class, 'editCalonSiswa']
    )->name('calon-siswa.profil');


    Route::post(
        '/calon-siswa/update/orang-tua/{id}',
        [CalonSiswaController::class, 'updateOrangTua']
    )->name('calon-siswa.update.orangtua');
    Route::post(
        '/calon-siswa/update/registrasi/{id}',
        [CalonSiswaController::class, 'updateRegistrasi']
    )->name('calon-siswa.update.registrasi');
    Route::post(
        '/calon-siswa/update-status/{id}',
        [CalonSiswaController::class, 'updateStatus']
    )->name('calon-siswa.update-status');

    Route::post('/calon-siswa/upload/{id}', [CalonSiswaController::class, 'updateUpload'])->name('calon-siswa.update.upload');
});


Route::middleware(['auth', 'role:keuangan'])->group(function () {});
