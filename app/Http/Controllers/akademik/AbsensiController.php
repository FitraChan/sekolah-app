<?php

namespace App\Http\Controllers\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterJadwal;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Gtk;
use App\Models\JamPelajaran;
use App\Models\Jurusan;
use App\Models\PenjadwalanHari;

use Illuminate\Support\Facades\DB;
use App\Models\LogModel;

class AbsensiController extends Controller
{
    public function index()
    {
        return view('akademik.absensi.index', [
            'side'  => 'absensi',
            'jam' => JamPelajaran::orderBy('id', 'desc')->get(),
            'jurusan' => Jurusan::orderBy('id', 'desc')->get(),
            'tahun' => TahunAjaran::orderBy('id', 'desc')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'mapel' => Mapel::orderBy('nama_mapel')->get(),
            'guru'  => Gtk::orderBy('nama_gtk')->get(),
        ]);
    }

    public function data(Request $request)
    {
        $query = MasterJadwal::with([
            'tahun',
            'kelas',
            'mapel',
            'guru'
        ]);

        if ($request->id_tahun) {
            $query->where('id_tahun', $request->id_tahun);
        }

        if ($request->id_kelas) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->id_jurusan) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('id_jurusan', $request->id_jurusan);
            });
        }

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
                    'nama_mapel'    => $item->mapel->nama_mapel ?? '',
                    'nama_gtk'      => $item->guru->nama_gtk ?? '',
                ];
            });

        return response()->json($data);
    }
}
