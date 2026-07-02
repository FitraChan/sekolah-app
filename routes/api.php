<?php

use App\Http\Controllers\pendaftaran\CalonSiswaController;


use Illuminate\Support\Facades\Route;


   Route::post('/calon-siswa/notifyPembayaran', [CalonSiswaController::class, 'notifyPembayaran'])
        ->name('calon-siswa.notifyPembayaran');



require __DIR__.'/web.php';