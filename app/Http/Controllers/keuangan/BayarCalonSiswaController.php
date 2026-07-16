<?php

namespace App\Http\Controllers\keuangan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\BayarCalonSiswa;
use App\Models\CalonSiswa;
use App\Models\DetBayarCalonSiswa;
use App\Models\Gelombang;
use App\Models\ItemBayar;
use Illuminate\Support\Facades\DB;
use App\Models\DetTempBayar;
use App\Models\Kelas;
use App\Models\LogModel;
use Barryvdh\DomPDF\Facade\Pdf;

class BayarCalonSiswaController extends Controller
{
    public function index()
    {
        $side = 'bayar-calon-siswa';
        $tahun = TahunAjaran::orderBy('id', 'desc')->get();
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        $gelombang = Gelombang::orderBy('nama_gelombang')->get();
        $itemBayar = ItemBayar::orderBy('nama_item')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();


        return view(
            'keuangan.bayar_calon_siswa.index',
            compact(
                'side',
                'tahun',
                'jurusan',
                'gelombang',
                'itemBayar',
                'kelas'

            )
        );
    }

    public function update(Request $request, $id)
    {
        try {

            $bayar = BayarCalonSiswa::findOrFail($id);

            // Simpan data lama untuk log

            $bayar->update([
                'no_kwitansi' => $request->no_kwitansi,
                'tgl_bayar'   => date('Y-m-d', strtotime($request->tgl_bayar ?? now())),
                'id_kasir'    => auth()->user()->id,
                'keterangan'  => $request->keterangan,
            ]);

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_bayar_regis',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($bayar),
                'serial' => url('update')
            ]);

            return response()->json([
                'success' => true,
                'title'   => 'Sukses',
                'msg'     => 'Data telah tersimpan'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'title'   => 'Peringatan',
                'msg'     => 'Gagal mengubah data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $user = auth()->user();



            DB::beginTransaction();

            $bayar = BayarCalonSiswa::findOrFail($id);

            $detailBayar = DetBayarCalonSiswa::where('id_bayar', $id)->get();

            // Log tb_bayar_regis
            LogModel::create([
                'tanggal'    => now(),
                'tabel'      => 'tb_bayar_regis',
                'aksi'       => 'delete',
                'user'       => $user->id,
                'ip'         => $request->ip(),
                'serial'     => $request->userAgent(),
                'keterangan' => json_encode($bayar),
            ]);

            // Log tb_det_bayar_regis
            LogModel::create([
                'tanggal'    => now(),
                'tabel'      => 'tb_det_bayar_regis',
                'aksi'       => 'delete',
                'user'       => $user->id,
                'ip'         => $request->ip(),
                'serial'     => $request->userAgent(),
                'keterangan' => json_encode($detailBayar),
            ]);

            // Hapus detail terlebih dahulu
            DetBayarCalonSiswa::where('id_bayar', $id)->delete();

            // Hapus master
            $bayar->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'title'   => 'Success',
                'msg'     => 'Proses berhasil dilakukan'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'title'   => 'Gagal',
                'msg'     => 'Proses gagal dilakukan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function data(Request $request)
    {
        $query = CalonSiswa::with([
            'tahunAjaran:id,thn_ajaran',
            'jurusan:id,nama_jurusan',
            'gelombang:id,nama_gelombang',
            'kelas:idx,nama_kelas',
        ]);

        if ($request->filled('tahun')) {
            $query->whereHas('tahunAjaran', function ($q) use ($request) {
                $q->where('thn_ajaran', $request->tahun);
            });
        }

        if ($request->filled('jurusan')) {
            $query->where('id_jurusan', $request->jurusan);
        }

        if ($request->filled('kelas')) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', 'like', '%' . $request->kelas . '%');
            });
        }

        if ($request->filled('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->keyword . '%');
            });
        }

        $data = $query
            ->orderByDesc('nipd')
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'id_calon_siswa' => $item->id_calon_siswa,
                    'no_daftar' => $item->no_daftar,
                    'nama_lengkap' => $item->nama_lengkap,
                    'jk' => $item->jk,
                    'no_hp' => $item->no_hp,

                    'tahun_ajaran' => [
                        'thn_ajaran' => $item->tahunAjaran?->thn_ajaran,
                    ],

                    'jurusan' => [
                        'nama_jurusan' => $item->jurusan?->nama_jurusan,
                    ],

                    'kelas' => [
                        'nama_kelas' => $item->kelas?->nama_kelas,
                    ],

                    'gelombang' => [
                        'nama_gelombang' => $item->gelombang?->nama_gelombang,
                    ],
                ];
            });

        return response()->json($data);
    }

    public function detail($nipd)
    {
        return BayarCalonSiswa::where('id_calon_siswa', $nipd)
            ->orderBy('tgl_bayar', 'desc')
            ->get()
            ->map(function ($row) {

                return [
                    'id'            => $row->id,
                    'id_calon_siswa'  => $row->id_calon_siswa,
                    'tahun_ajaran'  => $row->id_tahun ?? '',
                    'bulan'         => $row->id_bulan ?? '',
                    'tgl_bayar'     => $row->tgl_bayar,
                    'tot_bayar'     => $row->tot_bayar,
                    'total_kwajiban'  => $row->total_kwajiban,
                    'keterangan'    => $row->keterangan,
                    'no_kwitansi'   => $row->no_kwitansi,
                ];
            });
    }

    public function detailBayar($id)
    {
        return DetBayarCalonSiswa::where('id_bayar', $id)
            ->get()
            ->map(function ($row) {

                return [
                    'id'            => $row->id,
                    'id_bayar'  => $row->id_bayar ?? '',
                    'nama_item' => $row->itemBayar->nama_item ?? '',
                    'id_item' => $row->id_item ?? '',
                    'kwajiban_bayar'     => $row->kwajiban_bayar,
                    'potongan'     => $row->potongan,
                    'jml_bayar'  => $row->jml_bayar,

                ];
            });
    }

    public function setDefBulan(Request $request)
    {
        $idTahunAjaran = $request->id_thn_ajaran;
        $idJurusan     = $request->id_jurusan;
        $idTahun       = $request->id_tahun;
        $idBulan       = $request->id_bulan;

        $berhasil = 0;
        $gagal = 0;

        $siswa = CalonSiswa::where('id_thn_ajaran', $idTahunAjaran)
            ->where('id_jurusan', $idJurusan)
            ->get();

        DB::beginTransaction();

        try {

            foreach ($siswa as $row) {

                $bayar = BayarCalonSiswa::create([
                    'id_tahun' => $idTahun,
                    'id_bulan' => $idBulan,
                    'id_calon_siswa' => $row->no_daftar,
                ]);

                $detailTemplate = DetTempBayar::where('id_template', $row->id_template_bayar)
                    ->whereHas('itemBayar', function ($q) {
                        $q->whereIn('id_kategori', [1, 2]);
                    })
                    ->get();

                foreach ($detailTemplate as $item) {

                    $detail = DetBayarCalonSiswa::create([
                        'id_bayar'        => $bayar->id,
                        'id_item'         => $item->id_item,
                        'kwajiban_bayar'  => $item->jml_bayar,
                        'potongan'        => 0,
                        'jml_bayar'       => 0,
                    ]);

                    if ($detail) {
                        $berhasil++;
                    } else {
                        $gagal++;
                    }
                }

                $this->updateTotalBayar($bayar->id);
            }

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_bayar_regis',
                'aksi' => 'create',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($bayar ?? ""),
                'serial' => url('simpan')
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'title'   => 'Success',
                'msg'     => "Proses berhasil dilakukan pada Bulan {$idBulan}, Tahun {$idTahun}, Jurusan {$idJurusan}. Berhasil {$berhasil}, Gagal {$gagal}"
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'title'   => 'Error',
                'msg'     => $e->getMessage()
            ], 500);
        }
    }



    public function updateTotalBayar($id)
    {
        $total = DetBayarCalonSiswa::where('id_bayar', $id)
            ->sum('kwajiban_bayar')
            - DetBayarCalonSiswa::where('id_bayar', $id)
            ->sum('potongan');

        BayarCalonSiswa::where('id', $id)
            ->update([
                'total_kwajiban' => $total
            ]);
    }

    public function setLunas(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $no_daftar =   BayarCalonSiswa::where('id',$id)->first();
            $noKwitansi = empty($request->no_kwitansi)
                ? 'BK'
                : $request->no_kwitansi;

            $keterangan = $request->keterangan;

            // Update semua detail menjadi lunas
            DetBayarCalonSiswa::where('id_bayar', $id)
                ->update([
                    'jml_bayar' => DB::raw('kwajiban_bayar - potongan')
                ]);

            // Hitung total bayar
            $totalBayar = DetBayarCalonSiswa::where('id_bayar', $id)
                ->sum('jml_bayar');

            // Update header pembayaran
            BayarCalonSiswa::where('id', $id)
                ->update([
                    'no_kwitansi' => $noKwitansi,
                    'tgl_bayar'   => $request->tgl_bayar,
                    'keterangan'  => $keterangan,
                    'tot_bayar'   => $totalBayar,
                ]);

             CalonSiswa::where('no_daftar', $no_daftar->id_calon_siswa)->update(['status_daftar' => 3]);   

            DB::commit();

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_bayar_regis',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode(['id_bayar' => $id, 'no_kwitansi' => $noKwitansi, 'keterangan' => $keterangan]),
                'serial' => url('bayar/setLunas/' . $id)
            ]);

            return response()->json([
                'success' => true,
                'title'   => 'Sukses',
                'msg'     => 'Data telah tersimpan'
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'title'   => 'Peringatan',
                'msg'     => $e->getMessage()
            ], 500);
        }
    }

    public function simpanCicilan(Request $request)
    {
        DB::beginTransaction();

        try {
               

            $bayar = BayarCalonSiswa::create([
                'id_tahun'    => date('Y'),
                'id_bulan'    => date('m'),
                'id_calon_siswa'    => $request->id_csiswa,
                'no_kwitansi' => $request->no_kwitansi,
                'tgl_bayar'   => date('Y-m-d', strtotime($request->tgl_bayar ?? now())),
                'id_kasir'    => auth()->user()->id,
                'keterangan'  => $request->keterangan,
            ]);

            $idBayar = $bayar->id;

            $items = [
                [
                    'nominal' => $request->jml_pendaftaran,
                    'id_item' => 1,
                ],
                [
                    'nominal' => $request->jml_dpp,
                    'id_item' => 2,
                ],
                [
                    'nominal' => $request->jml_mos,
                    'id_item' => 3,
                ],
                [
                    'nominal' => $request->jml_seragam,
                    'id_item' => 5,
                ],
            ];

            foreach ($items as $item) {

                if ($item['nominal'] > 0) {

                    $detail =  DetBayarCalonSiswa::create([
                        'id_bayar'   => $idBayar,
                        'id_item'    => $item['id_item'],
                        'jml_bayar'  => $item['nominal'],
                        'id_cicilan' => $request->cicilan,
                    ]);

                    if($item['id_item'] == 1){

                        CalonSiswa::where('no_daftar', $request->id_csiswa)->update(['status_daftar' => 0]);  
                    }


                     if($item['id_item'] == 2){

                        CalonSiswa::where('no_daftar', $request->id_csiswa)->update(['status_daftar' => 1]);  
                    }
            //  
                }
            }

            $this->updateKewajiban($idBayar);
            $this->updateBayar($idBayar);

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_bayar_regis',
                'aksi' => 'create',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($bayar),
                'serial' => url('simpanCicilan')
            ]);

           

            DB::commit();

            return response()->json([
                'success' => true,
                'title'   => 'Sukses',
                'bayar'   => $bayar,
                'detail'  => $items,
                'msg'     => 'Data telah tersimpan. ID : ' . $idBayar,
                'id'      => $idBayar,
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'title'   => 'Peringatan',
                'msg'     => $e->getMessage(),
            ], 500);
        }
    }

    public function updateBayar($id)
    {
        $total = DetBayarCalonSiswa::where('id_bayar', $id)
            ->sum('jml_bayar');

        return BayarCalonSiswa::where('id', $id)
            ->update([
                'tot_bayar' => $total
            ]);
    }
    public function updateKewajiban($id)
    {
        $total = DetBayarCalonSiswa::where('id_bayar', $id)
            ->selectRaw('COALESCE(SUM(kwajiban_bayar),0) - COALESCE(SUM(potongan),0) as total')
            ->value('total');

        return BayarCalonSiswa::where('id', $id)
            ->update([
                'total_kwajiban' => $total
            ]);
    }

    public function createReportPdf(Request $request, $id)
    {
        $hasil = DB::table('tb_bayar_regis')
            ->join('tb_det_bayar_regis', 'tb_bayar_regis.id', '=', 'tb_det_bayar_regis.id_bayar')
            ->join('tb_itembayar', 'tb_det_bayar_regis.id_item', '=', 'tb_itembayar.id')
            ->select(
                'tb_bayar_regis.id_calon_siswa',
                'tb_det_bayar_regis.id_item',
                'tb_itembayar.nama_item',
                DB::raw('SUM(tb_bayar_regis.tot_bayar) as SUMT1'),
                DB::raw('SUM(tb_bayar_regis.total_kwajiban) as SUMK1'),
                DB::raw('SUM(tb_det_bayar_regis.jml_bayar) as SUMB'),
                DB::raw('SUM(tb_det_bayar_regis.kwajiban_bayar) as SUMK2'),
                DB::raw('SUM(tb_det_bayar_regis.potongan) as SUMP')
            )
            ->where('tb_bayar_regis.id_calon_siswa', $id)
            ->groupBy(
                'tb_bayar_regis.id_calon_siswa',
                'tb_det_bayar_regis.id_item',
                'tb_itembayar.nama_item'
            )
            ->orderBy('tb_det_bayar_regis.id_item')
            ->get();

        $atas = CalonSiswa::with('kelas', 'jurusan')->where('no_daftar', $id)->first();

        $profile = DB::table('tb_profile')->first();

        $data = compact('hasil', 'atas', 'profile');



        if ($request->has('grid')) {
            return response()->json($data);
        }

        //return view('keuangan.bayar.rptkewajiban_pdf', $data);
        $pdf = Pdf::loadView(
            'keuangan.bayar_calon_siswa.rptkewajiban_pdf',
            $data
        )->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-kewajiban-' . $atas->nipd . '.pdf');
    }
}
