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
use App\Models\Siswa;
use App\Models\LogModel;







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


            LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_det_temp_bayar',
            'aksi' => 'set default',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($data),
            'serial' => url('set-default/' . $id)
        ]);
        });

        

        return response()->json([
            'success' => true,
            'title'   => 'Sukses',
            'msg'     => 'Data telah tersimpan'
        ]);
    }
    public function store(Request $request)
    {
        $template = TemplateBayar::create([
        'id_tahun'      => $request->id_tahun,
        'id_jurusan'    => $request->id_jurusan,
        'keterangan'    => $request->keterangan,
        'jns_kelas'     => $request->jns_kelas,
        'id_gelombang'  => $request->id_gelombang,
        'sts'           => $request->sts ?? 1,
    ]);

    Siswa::where('id_thn_ajaran', $template->id_tahun)
        ->whereNull('id_template_bayar')
        ->update([
            'id_template_bayar' => $template->id
        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_template_bayar',
            'aksi' => 'create',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($template),
            'serial' => url('simpan')
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

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_template_bayar',
            'aksi' => 'update',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($row),
            'serial' => url('ubah/' . $id)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function delete($id)
    {
       $template = TemplateBayar::findOrFail($id);
       $template->delete();

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_template_bayar',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($template),
            'serial' => url('hapus/' . $id)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function deleteDetail($id)
    {
        $detail = DetTempBayar::findOrFail($id);
        $detail->delete();

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_det_temp_bayar',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($detail),
            'serial' => url('hapus-detail/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil dihapus'
        ]);
    }


    public function storeDetail(Request $request)
    {
        $detail = DetTempBayar::create([
            'id_template' => $request->id_template,
            'id_item'     => $request->id_item,
            'jml_bayar'   => $request->jml_bayar,
            'ket_bayar'   => $request->ket_bayar,
        ]);

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_det_temp_bayar',
            'aksi' => 'create',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($detail),
            'serial' => url('simpan-detail')
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

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_det_temp_bayar',
            'aksi' => 'update',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($row),
            'serial' => url('ubah-detail/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Data berhasil diupdate'
        ]);
    }
}
