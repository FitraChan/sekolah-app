<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use App\Models\TemplateBayar;
use App\Models\TahunAjaran;
use App\Models\ItemBayar;
use Illuminate\Support\Facades\DB;
use App\Models\Jurusan;
use App\Models\DetTempBayar;
use App\Models\Gelombang;





use Illuminate\Http\Request;

class TemplateBayarController extends Controller
{

    public function index()
    {
        $side = 'template-bayar';

        $tahun = TahunAjaran::orderBy('id', 'desc')->get();

        $jurusan = Jurusan::orderBy('nama_jurusan')->get();

        $gelombang = Gelombang::orderBy('idx')->get();


        $itemBayar = ItemBayar::orderBy('id', 'desc')->get();


        return view(
            'keuangan.template_bayar.index',
            compact(
                'side',
                'tahun',
                'jurusan',
                'gelombang',
                'itemBayar'
            )
        );
    }
    public function data()
    {
        return TemplateBayar::with([
            'tahunAjaran:id,thn_ajaran',
            'jurusan:id,nama_jurusan',
            'gelombang:id,nama_gelombang'
        ])->orderBy('id_tahun', 'desc')->get();
    }

    public function detail($idTemplate)
    {
        return DetTempBayar::with([
            'itemBayar.kategori:id,nama_kategori',
            'itemBayar.periode:id,nama_periode'
        ])
            ->where('id_template', $idTemplate)
            ->get()
            ->map(function ($row) {
                return [
                    'id'             => $row->id,
                    'id_item'        => $row->id_item,
                    'nama_item'      => $row->itemBayar->nama_item ?? '',
                    'kategori'       => $row->itemBayar->kategori->nama_kategori ?? '',
                    'periode'        => $row->item->periode->nama_periode ?? '',
                    'jml_bayar'      => $row->jml_bayar,
                    'ket_bayar'      => $row->ket_bayar,
                ];
            });
    }


    public function setDefault($id = null)
    {
        DB::transaction(function () use ($id) {
            DetTempBayar::where('id_template', $id)->delete();
            $items = ItemBayar::all();
            $data = [];
            foreach ($items as $item) {
                $data[] = [
                    'id_template' => $id,
                    'id_item'     => $item->id,
                    'jml_bayar'   => $item->def_value,
                    'ket_bayar'   => $item->keterangan,
                ];
            }

            if (!empty($data)) {
                DetTempBayar::insert($data);
            }
        });

        return response()->json([
            'success' => true,
            'title'   => 'Sukses',
            'msg'     => 'Data telah tersimpan'
        ]);
    }
    public function store(Request $request)
    {
        TemplateBayar::create([
            'id_tahun'      => $request->id_tahun,
            'id_jurusan'    => $request->id_jurusan,
            'keterangan'    => $request->keterangan,
            'jns_kelas'     => $request->jns_kelas,
            'id_gelombang'  => $request->id_gelombang,
            'sts'           => $request->sts ?? 1,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function update(Request $request, $id)
    {
        $row = TemplateBayar::findOrFail($id);

        $row->update([

            'id_tahun'      => $request->id_tahun,

            'id_jurusan'    => $request->id_jurusan,

            'keterangan'    => $request->keterangan,

            'jns_kelas'     => $request->jns_kelas,

            'id_gelombang'  => $request->id_gelombang,

            'sts'           => $request->sts,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function delete($id)
    {
        TemplateBayar::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }

    public function deleteDetail($id)
    {
        DetTempBayar::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil dihapus'
        ]);
    }


    public function storeDetail(Request $request)
    {
        DetTempBayar::create([
            'id_template' => $request->id_template,
            'id_item'     => $request->id_item,
            'jml_bayar'   => $request->jml_bayar,
            'ket_bayar'   => $request->ket_bayar,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil disimpan'
        ]);
    }

    public function updateDetail(Request $request, $id)
    {
        $row = DetTempBayar::findOrFail($id);

        $row->update([
            'id_item'   => $request->id_item,
            'jml_bayar' => $request->jml_bayar,
            'ket_bayar' => $request->ket_bayar,
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil diupdate'
        ]);
    }
}
