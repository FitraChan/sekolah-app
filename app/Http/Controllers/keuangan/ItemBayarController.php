<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\KatPeriodeBayar;
use App\Models\KatItemBayar;
use App\Models\ItemBayar;
use Illuminate\Http\Request;

class ItemBayarController extends Controller
{
    public function index()
    {
        $side = 'item-bayar';

        $kategori = KatItemBayar::orderBy('nama_kategori')->get();

        $periode = KatPeriodeBayar::orderBy('nama_kategori')->get();

        return view(
            'keuangan.item_bayar.index',
            compact(
                'side',
                'kategori',
                'periode'
            )
        );
    }

    public function data()
    {
        return ItemBayar::leftJoin(
            'tb_kat_itembayar',
            'tb_itembayar.id_kategori',
            '=',
            'tb_kat_itembayar.id'
        )
            ->leftJoin(
                'tb_kat_periodebayar',
                'tb_itembayar.id_kat_periode',
                '=',
                'tb_kat_periodebayar.id'
            )
            ->select(
                'tb_itembayar.*',
                'tb_kat_itembayar.nama_kategori as kategori',
                'tb_kat_periodebayar.nama_kategori as periode'
            )
            ->get();
    }

    public function store(Request $request)
    {
        ItemBayar::create([

            'nama_item'       => $request->nama_item,

            'id_kategori'     => $request->id_kategori,

            'id_kat_periode'  => $request->id_kat_periode,

            'keterangan'      => $request->keterangan,

            'def_value'       => $request->def_value,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $row = ItemBayar::findOrFail($id);

        $row->update([

            'nama_item'       => $request->nama_item,

            'id_kategori'     => $request->id_kategori,

            'id_kat_periode'  => $request->id_kat_periode,

            'keterangan'      => $request->keterangan,

            'def_value'       => $request->def_value,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function destroy($id)
    {
        ItemBayar::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
