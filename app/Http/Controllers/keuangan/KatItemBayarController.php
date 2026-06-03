<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KatItemBayar;
use Illuminate\Support\Facades\Auth;



class KatItemBayarController extends Controller
{
    public function index()
    {
        $side = 'kat-item-bayar';

        return view(
            'keuangan.kat_item_bayar.index',
            compact('side')
        );
    }

    public function data()
    {
        return response()->json(
            KatItemBayar::orderBy('id', 'asc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        KatItemBayar::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function edit($id)
    {
        return response()->json(
            KatItemBayar::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required'
        ]);

        $row = KatItemBayar::findOrFail($id);

        $row->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        KatItemBayar::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
