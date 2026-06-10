<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterJadwal;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Gtk;
use App\Models\LogModel;

class MasterJadwalController extends Controller
{
    public function index()
    {
        return view('akademik.master-jadwal.index', [
            'side'  => 'master-jadwal',
            'tahun' => TahunAjaran::orderBy('id', 'desc')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'mapel' => Mapel::orderBy('nama_mapel')->get(),
            'guru'  => Gtk::orderBy('nama_gtk')->get(),
        ]);
    }

    public function data()
    {
        $data = MasterJadwal::with([
            'tahun',
            'kelas',
            'mapel',
            'guru'
        ])
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($item) {

            return [
                'id'                 => $item->id,
                'id_tahun'           => $item->id_tahun,
                'semester'           => $item->semester,
                'id_kelas'           => $item->id_kelas,
                'id_mapel'           => $item->id_mapel,
                'id_gtk'             => $item->id_gtk,
                'jml_jam'            => $item->jml_jam,
                'angkatan'           => $item->angkatan,

                'tahun_ajaran'       => $item->tahun->thn_ajaran ?? '',
                'nama_kelas'         => $item->kelas->nama_kelas ?? '',
                'nama_mapel'         => $item->mapel->nama_mapel ?? '',
                'nama_gtk'           => $item->guru->nama_gtk ?? '',
            ];
        });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $jadwal = MasterJadwal::create([
            'id_tahun' => $request->id_tahun,
            'semester' => $request->semester,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'jml_jam'  => $request->jml_jam,
            'id_gtk'   => $request->id_gtk,
            'angkatan' => $request->angkatan,
        ]);

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'create',
            'user'        => auth()->user()->id,
            'ip'          => $request->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/store')
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil disimpan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = MasterJadwal::findOrFail($id);

        $jadwal->update([
            'id_tahun' => $request->id_tahun,
            'semester' => $request->semester,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'jml_jam'  => $request->jml_jam,
            'id_gtk'   => $request->id_gtk,
            'angkatan' => $request->angkatan,
        ]);

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'update',
            'user'        => auth()->user()->id,
            'ip'          => $request->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/update/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $jadwal = MasterJadwal::findOrFail($id);

        $jadwal->delete();

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'delete',
            'user'        => auth()->user()->id,
            'ip'          => request()->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/delete/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil dihapus'
        ]);
    }
}