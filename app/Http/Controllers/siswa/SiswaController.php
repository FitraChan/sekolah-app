<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Gelombang;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Agama;
use App\Models\ItemBayar;
use App\Models\Pekerjaan;
use App\Models\StatusDaftar;
use App\Models\IpaymuBayar;
use App\Models\IpaymuDetBayar;


use Illuminate\Support\Facades\Auth;
use App\Models\LogModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\BayarCalonSiswa;
use App\Models\DetBayarCalonSiswa;

class SiswaController extends Controller
{
    public function index()
    {
        $side = 'siswa';

       

        $jurusan = Jurusan::all();

        return view('pendaftaran.calon_siswa.index', compact(
            'side',            
            'jurusan'
        ), ['side'  => 'siswa']);
    }

    public function create()
    {
        $side = 'siswa';
        $rows = new Siswa();           
        $thn = TahunAjaran::orderBy('id', 'desc')->get();
        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();
      
        return view(
            'siswa.edit_siswa',
            compact(
                'side',
                'rows',                
                'thn',
                'lists',
                'jobs',
                'agama',          
            )
        );
    }

     public function edit($id)
    {
        $side = 'siswa';

        $rows = Siswa::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */
        $gel = Gelombang::orderBy('idx', 'asc')->get();
        $thn = TahunAjaran::orderBy('id', 'desc')->get();
        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();
        $sts_daftar = StatusDaftar::orderBy('keterangan', 'asc')->get();

        $dataIpaymu = IpaymuBayar::with([
            'detailBayar',
            'calonSiswa'
        ])->where('id_calon_siswa', $id)->first();



        $bukti = CalonSiswa::with('buktiPembayaran')
            ->where('id', $id)
            ->get();

             $itemBayar = ItemBayar::where('id_kategori', 2)
            ->get();
        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        $stsdaftar =
            $rows->statusDaftar->keterangan
            ?? 'Belum Ada';
        return view(
            'pendaftaran.calon_siswa.edit_calon_siswa',
            compact(
                'side',
                'rows',
                'gel',
                'thn',
                'lists',
                'jobs',
                'agama',
                'sts_daftar',
                'stsdaftar',
                'bukti',
                'itemBayar',
                'dataIpaymu'
            )
        );
    }
}
