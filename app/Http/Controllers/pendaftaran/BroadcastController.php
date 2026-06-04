<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Broadcast;
use Illuminate\Support\Facades\DB;
use App\Models\LogModel;

class BroadcastController extends Controller
{
    //
      public function index()
    {
        $side = 'broadcast';

            $broadcast = Broadcast::orderBy('id', 'desc')->get();

        return view('pendaftaran.broadcast.index', compact(
            'side','broadcast'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | DATA
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        $data = Broadcast::latest('id')
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'judul' => $item->judul,
                    'pesan' => $item->pesan,
                    'iduser' => $item->iduser,
                    'tgl_update' => optional($item->tgl_update)
                        ->format('d-m-Y H:i'),
                ];
            });

        return response()->json($data);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'judul' => 'required',

            'pesan' => 'required',

        ]);

      $broadcast = Broadcast::create([

            'judul' => $request->judul,

            'pesan' => $request->pesan,

            'iduser' => auth()->id(),

        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_broadcast',
            'aksi' => 'create',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($broadcast),
            'serial' => url('simpan')
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Broadcast berhasil ditambahkan'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $id)
    {
        $request->validate([

            'judul' => 'required',

            'pesan' => 'required',

        ]);

        $broadcast = Broadcast::findOrFail($id);

        $broadcast->update([

            'judul' => $request->judul,

            'pesan' => $request->pesan,

            'iduser' => auth()->id(),

        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_broadcast',
            'aksi' => 'update',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($broadcast),
            'serial' => url('ubah/' . $id)
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Broadcast berhasil diupdate'

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function delete($id)
    {
        $broadcast = Broadcast::findOrFail($id);

        $broadcast->delete();

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_broadcast',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($broadcast),
            'serial' => url('hapus/' . $id)
        ]);

        return response()->json([

            'success' => true,

            'message' => 'Broadcast berhasil dihapus'

        ]);
    }

    public function kirimSemua(Request $request)
{
    $broadcast = DB::table('tb_broadcast')
        ->where('id', $request->id_broadcast)
        ->first();

        

    if(!$broadcast)
    {
        return response()->json([
            'message' => 'Broadcast tidak ditemukan'
        ]);
    }

    $siswa = DB::table('tb_tmp_siswa')
        ->whereIn('email',['fitrachan26@gmail.com','putuj0708@gmail.com'])
       // ->whereNotNull('email')
        ->get();

    foreach($siswa as $row)
    {
        DB::table('tb_antrian_email')->insert([

            'email'         => $row->email,
            'judul'         => $broadcast->judul,
            'pesan'         => $broadcast->pesan,
            'status'        => 0,
            'created_at'    => now(),
            'updated_at'    => now()

        ]);
    }

    return response()->json([
        'message' => 'Broadcast berhasil masuk antrian'
    ]);
}
}
