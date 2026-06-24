<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterJadwal;
use App\Models\Gtk;
use App\Models\TransAjar;
use App\Models\Quiz;





class PenilaianGuruController extends Controller
{
    public function index()
    {
        $konfig = konfig();
        $smt = ($konfig['smt'] ?? 1) == 1 ? 'Ganjil' : 'Genap';
        return view('guru.pbm.index', [
            'side'  => 'pbm',
            'smt'   => $smt,
            'thn' => $konfig['id_tahun']

        ]);
    }

    public function data(Request $request)
    {

        $gtk = Gtk::where('user_id', auth()->user()->id)->first();

        $query = MasterJadwal::with([
            'tahun',
            'kelas',
            'mapel',
            'guru'
        ]);


        $query->where('id_tahun', konfig()['id_tahun']);

        $query->where('semester', konfig()['smt']);

        $query->where('id_gtk', $gtk->id);


        $data = $query
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {

                return [
                    'id'            => $item->id,
                    'id_tahun'      => $item->id_tahun,
                    'semester'      => $item->semester,
                    'id_kelas'      => $item->id_kelas,
                    'id_mapel'      => $item->id_mapel,
                    'id_gtk'        => $item->id_gtk,
                    'jml_jam'       => $item->jml_jam,
                    'angkatan'      => $item->angkatan,
                    'tahun_ajaran'  => $item->tahun->thn_ajaran ?? '',
                    'nama_kelas'    => $item->kelas->nama_kelas ?? '',
                    'kelas'         => $item->kelas->kelas ?? '',
                    'nama_mapel'    => $item->mapel->nama_mapel ?? '',
                    'nama_gtk'      => $item->guru->nama_gtk ?? '',
                ];
            });

        return response()->json($data);
    }

   


    public function dataMateri(Request $request, $id = null)
    {
      $data['isi'] = TransAjar::withCount([
        'hadir as H',
        'izin as I',
        'sakit as S',
        'alfa as A',
            ])
            ->where('idjadwal', $id)
            ->orderBy('idpertemuan')
            ->orderBy('tgl')
            ->get()
            ->map(function ($item) {

                return [
                    'id'                  => $item->id,
                    'idjadwal'            => $item->idjadwal,
                    'idpertemuan'         => $item->idpertemuan,
                    'tgl'                 => $item->tgl
                        ? date('d-m-Y', strtotime($item->tgl))
                        : '',
                    'judul_materi'        => $item->judul_materi ?? '',
                    'nama_guru_pengganti' => $item->guruPengganti->nama_gtk ?? '',
                    'judul_tugas'         => $item->judul_tugas ?? '',
                    'keterangan'          => $item->keterangan ?? '',

                    'H' => $item->H,
                    'I' => $item->I,
                    'S' => $item->S,
                    'A' => $item->A,
                ];
            });

        $data['master'] = MasterJadwal::with(['kelas', 'mapel', 'guru'])
            ->where('id', $id)
            ->first();

        $data['guru'] = Gtk::select('id', 'nama_gtk')->get();

        $data['ujian'] = Quiz::where('master_kelas_id', $id)->get();
        $data['id'] = $id;

         return view('guru.materi_pbm.index', $data);

       //return response()->json($data);
    }
}
