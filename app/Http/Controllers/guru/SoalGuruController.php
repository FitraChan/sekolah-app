<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gtk;
use App\Models\Soal;
use App\Models\JenisSoal;
use App\Models\MasterJadwal;
use App\Models\LogModel;
use Illuminate\Support\Facades\DB;

class SoalGuruController extends Controller
{
    public function index()
	{

        $konfig = konfig();

        $smt = $konfig['smt'];
        $id_tahun = $konfig['id_tahun'];

        $idGtk = Gtk::where('user_id', auth()->id())->first();
        $jenisSoal = JenisSoal::orderBy('id')->get();
        $mapel = MasterJadwal::with(['mapel', 'kelas'])
            ->where('id_gtk', $idGtk->id)
            ->where('id_tahun', $id_tahun)
            ->where('semester', $smt)
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'id_mapel'    => $item->id_mapel,
                    'nama_mapel'  => $item->mapel->nama_mapel,
                    'id_gtk'      => $item->id_gtk,
                    'kelas'       => $item->kelas->kelas,
                    'nama_kelas'  => $item->kelas->nama_kelas,
                ];
            });

        return view('guru.soal.index', [
            'side'       => 'soalGuru',
            'thn'        => $id_tahun,
            'smt'        => $smt == 1 ? 'Ganjil' : 'Genap',
            'nama_gtk'   => $idGtk->nama_gtk,
            'jenis_soal' => $jenisSoal,
            'mapel'      => $mapel,
        ]);


    }

    public function data()
    {
        
        $idGtk = Gtk::where('user_id', auth()->id())->first();

        $data = Soal::with([
        'mapel',
        'jenisSoal'
        ])
        ->where('lecture_id', $idGtk->id)
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($item) {

            return [
                'id'              => $item->id,
                'judul_soal'      => $item->judul_soal,
                'soal'            => $item->soal,
                'semester'        => $item->smt,
                'jenis_soal'      => $item->jenisSoal?->jenis_soal,
                'nama_mapel'      => $item->mapel?->nama_mapel,
                'jawaban_benar'   => $item->jawaban_benar,
                'created_at'      => optional($item->created_at)->format('d-m-Y H:i'),
                'updated_at'      => optional($item->updated_at)->format('d-m-Y H:i'),
            ];
        });

        return response()->json($data);
    }

    public function create(){

        $konfig = konfig();

        $smt = $konfig['smt'];
        $id_tahun = $konfig['id_tahun'];

        $idGtk = Gtk::where('user_id', auth()->id())->first();
        $jenisSoal = JenisSoal::orderBy('id')->get();
        $mapel = MasterJadwal::with(['mapel', 'kelas'])
            ->where('id_gtk', $idGtk->id)
            // ->where('id_tahun', $id_tahun)
            // ->where('semester', $smt)
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'id_mapel'    => $item->id_mapel,
                    'nama_mapel'  => $item->mapel->nama_mapel,
                    'id_gtk'      => $item->id_gtk,
                    // 'kelas'       => $item->kelas->kelas,
                    // 'nama_kelas'  => $item->kelas->nama_kelas,
                ];
            });

        return view('guru.soal.tambah', [
            'side'       => 'soalGuru',
            'thn'        => $id_tahun,
            'smt'        => $smt == 1 ? 'Ganjil' : 'Genap',
            'nama_gtk'   => $idGtk->nama_gtk,
            'jenis_soal' => $jenisSoal,
            'mapel'      => $mapel,
        ]);

    }

     public function edit($id){
        $konfig = konfig();

        $smt = $konfig['smt'];
        $id_tahun = $konfig['id_tahun'];

        $idGtk = Gtk::where('user_id', auth()->id())->first();
        $jenisSoal = JenisSoal::orderBy('id')->get();
        $mapel = MasterJadwal::with(['mapel', 'kelas'])
            ->where('id_gtk', $idGtk->id)        
            ->get()
            ->map(function ($item) {
                return [
                    'id'          => $item->id,
                    'id_mapel'    => $item->id_mapel,
                    'nama_mapel'  => $item->mapel->nama_mapel,
                    'id_gtk'      => $item->id_gtk,
                    // 'kelas'       => $item->kelas->kelas,
                    // 'nama_kelas'  => $item->kelas->nama_kelas,
                ];
            });

            $hasil = Soal::with([
                'mapel',
                'jenisSoal'
            ])->find($id);

        return view('guru.soal.tambah', [
            'side'       => 'soalGuru',
            'thn'        => $id_tahun,
            'smt'        => $smt == 1 ? 'Ganjil' : 'Genap',
            'nama_gtk'   => $idGtk->nama_gtk,
            'jenis_soal' => $jenisSoal,
            'mapel'      => $mapel,
            'row'      => $hasil,
        ]);

     }
}
