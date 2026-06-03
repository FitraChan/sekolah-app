<?php

namespace App\Http\Controllers\keuangan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KatPeriodeBayar;
use Illuminate\Support\Facades\Auth;

class KatPeriodeBayarController extends Controller
{
     public function index()
    {
        $side = 'kat-periode-bayar';

        return view(
            'keuangan.kat_periode_bayar.index',
            compact('side')
        );
    }

    public function data()
    {
        return KatPeriodeBayar::orderBy('nama_kategori')
            ->get();
    }

    public function store(Request $request)
    {
        KatPeriodeBayar::create([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $row = KatPeriodeBayar::findOrFail($id);

        $row->update([
            'nama_kategori' => $request->nama_kategori
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        $row = KatPeriodeBayar::findOrFail($id);

        $row->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
