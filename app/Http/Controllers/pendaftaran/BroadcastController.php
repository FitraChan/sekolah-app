<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Broadcast;

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

        Broadcast::create([

            'judul' => $request->judul,

            'pesan' => $request->pesan,

            'iduser' => auth()->id(),

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

        return response()->json([

            'success' => true,

            'message' => 'Broadcast berhasil dihapus'

        ]);
    }
}
