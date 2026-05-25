<?php

namespace App\Http\Controllers\Pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    public function index()
    {
        return view('pendaftaran.gelombang.index',[ 'side'  => 'gelombang']);
    }

    public function data()
    {
        $data = Gelombang::orderBy('idx', 'asc')
            ->get()
            ->map(function ($item) {

                return [

                    'id' => $item->id,

                    'nama_gelombang' => $item->nama_gelombang,

                                        'id_tahun' => $item->id_tahun,


                    'awal' => $item->awal,

                    'akhir' => $item->akhir,

                   'is_current' => $item->is_current,

                    'idx' => $item->idx,
                                    
                   
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        Gelombang::create([
            'id_tahun' => $request->id_tahun,
            'nama_gelombang' => $request->nama_gelombang,
            'awal' => $request->awal,
            'akhir' => $request->akhir,
            'is_current' => $request->is_current,
            'idx' => $request->idx,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $gelombang = Gelombang::findOrFail($id);

        $gelombang->update([
            'id_tahun' => $request->id_tahun,
            'nama_gelombang' => $request->nama_gelombang,
            'awal' => $request->awal,
            'akhir' => $request->akhir,
            'is_current' => $request->is_current,
            'idx' => $request->idx,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        Gelombang::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}