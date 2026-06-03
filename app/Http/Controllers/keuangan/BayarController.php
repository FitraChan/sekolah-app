<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Bayar;



class BayarController extends Controller
{
    public function index()
    {
        $side = 'bayar';
        $tahun = TahunAjaran::orderBy('id', 'desc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();


        return view(
            'keuangan.bayar.index',
            compact(
                'side',
                'tahun',
                'jurusan',

            )
        );
    }

    public function data()
    {
        return Siswa::with([
            'tahunAjaran:id,thn_ajaran',
            'jurusan:id,nama_jurusan',
            'gelombang:id,nama_gelombang',
            'kelas:idx,nama_kelas',
            'templateBayar:id,keterangan'
        ])
        ->orderBy('id_thn_ajaran', 'desc')
        ->get();
    }

    public function detail($nipd)
    {
        return Bayar::where('id_siswa', $nipd)
        ->orderBy('tgl_bayar', 'desc')
        ->get()
        ->map(function ($row) {

            return [
                'id'            => $row->id,
                'tahun_ajaran'  => $row->id_tahun ?? '',
                'bulan'         => $row->id_bulan ?? '',
                'tgl_bayar'     => $row->tgl_bayar,
                'tot_bayar'     => $row->tot_bayar,
                'tot_kwajiban'  => $row->tot_kwajiban,
                'keterangan'    => $row->keterangan,
                'no_kwitansi'   => $row->no_kwitansi,
            ];
        });
    }
}
