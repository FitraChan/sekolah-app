<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gtk;
use App\Models\Quiz;
use App\Models\JenisSoal;
use App\Models\MasterJadwal;
use App\Models\TahunAjar;

class UjianController extends Controller
{
    public function index()
    {
       $konfig = konfig();

        $smt = $konfig['smt'];
        $idGtk = Gtk::where('user_id', auth()->id())->first();

        $id_tahun = $konfig['id_tahun'];

        $jenisSoal = JenisSoal::orderBy('id')->get();

        $isi = Quiz::with(['masterJadwal'])
        ->whereHas('masterJadwal', function ($q) use ($id_tahun, $smt,$idGtk) {
                $q->where('id_tahun', $id_tahun)
                ->where('semester', $smt)
                ->where('id_gtk',$idGtk->id);
            })            
            ->orderBy('id')
            ->get()
            ->map(function ($item) {

                return [
                    'id' =>$item->id,
                    'judul' =>$item->judul,   
                    'tgl_quiz' =>\Carbon\Carbon::parse($item->tgl_quiz)->format('d-m-Y'),
                    'kelas' =>$item->masterJadwal->kelas->kelas.' '.$item->masterJadwal->kelas->nama_kelas,
                    'nama_mapel' =>$item->masterJadwal->mapel->nama_mapel,
                    'updated_at' =>$item->updated_at,
                ];

             });

        $mapel = MasterJadwal::with(['mapel','kelas'])
            ->where('id_gtk', $idGtk->id)
            ->where('id_tahun', $id_tahun)
            ->where('semester', $smt)
            ->get()
             ->map(function ($item) {

                return [
                    'id' =>$item->id,
                    'id_mapel' =>$item->id_mapel,
                    'nama_mapel' =>$item->mapel->nama_mapel,
                    'id_gtk' =>$item->id_gtk,
                    'kelas' =>$item->kelas->kelas,
                    'nama_kelas' =>$item->kelas->nama_kelas,


                ];

             });

        return view('guru.ujian.index', [
            'side'        => 'ujianGuru',
            'thn'         => $id_tahun,
            'smt'         => $smt == 1 ? 'Ganjil' : 'Genap',
            'nama_gtk'    => $idGtk->nama_gtk,
            'jenis_soal'  => $jenisSoal,
            'isi'         => $isi,
            'mapel'       => $mapel,
        ]);
    }
}
