<?php

namespace App\Http\Controllers\akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jurusan;
use App\Models\LogModel;

class JurusanController extends Controller
{
     public function index()
    {
        return view('akademik.jurusan.index', [
            'side' => 'jurusan'
        ]);
    }

    public function data()
    {
        return response()->json(
            Jurusan::orderBy('nama_jurusan')->get()
        );
    }

    public function store(Request $request)
    {
        $jurusan = Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
            'jumlah_siswa' => $request->jumlah_siswa,
            'singkatan'    => $request->singkatan,
        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_jurusan',
            'aksi' => 'create',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($jurusan),
            'serial' => url('jurusan/store')
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $jurusan->update([
            'nama_jurusan' => $request->nama_jurusan,
            'jumlah_siswa' => $request->jumlah_siswa,
            'singkatan'    => $request->singkatan,
        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_jurusan',
            'aksi' => 'update',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($jurusan),
            'serial' => url('jurusan/update/'.$id)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $jurusan->delete();

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_jurusan',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($jurusan),
            'serial' => url('jurusan/delete/'.$id)
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
