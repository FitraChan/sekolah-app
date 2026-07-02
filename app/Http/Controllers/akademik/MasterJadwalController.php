<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterJadwal;
use App\Models\TahunAjaran;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Gtk;
use App\Models\JamPelajaran;
use App\Models\Jurusan;
use App\Models\PenjadwalanHari;

use Illuminate\Support\Facades\DB;
use App\Models\LogModel;

class MasterJadwalController extends Controller
{
    public function index()
    {
        return view('akademik.master_jadwal.index', [
            'side'  => 'master-jadwal',
            'jam' => JamPelajaran::orderBy('id', 'desc')->get(),
            'jurusan' => Jurusan::orderBy('id', 'desc')->get(),
            'tahun' => TahunAjaran::orderBy('id', 'desc')->get(),
            'kelas' => Kelas::orderBy('nama_kelas')->get(),
            'mapel' => Mapel::orderBy('nama_mapel')->get(),
            'guru'  => Gtk::orderBy('nama_gtk')->get(),

        ]);
    }

    public function data(Request $request)
    {
        $query = MasterJadwal::with([
            'tahun',
            'kelas',
            'mapel',
            'guru'
        ]);

        if ($request->id_tahun) {
            $query->where('id_tahun', $request->id_tahun);
        }

        if ($request->id_kelas) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->id_jurusan) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('id_jurusan', $request->id_jurusan);
            });
        }

        $data = $query
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($item) {

                return [
                    'id'            => $item->id,
                    'id_tahun'      => $item->id_tahun,
                    'semester'      => $item->semester,
                    'id_kelas'      => $item->id_kelas,
                    'id_mapel'      => $item->id_mapel,
                    'id_gtk'        => $item->id_gtk,
                    'jml_jam'       => $item->jml_jam,
                    'angkatan'      => $item->angkatan,

                    'tahun_ajaran'  => $item->tahun->thn_ajaran ?? '',
                    'nama_kelas'    => $item->kelas->nama_kelas ?? '',
                    'nama_mapel'    => $item->mapel->nama_mapel ?? '',
                    'nama_gtk'      => $item->guru->nama_gtk ?? '',
                ];
            });

        return response()->json($data);
    }

    public function dataDetail(Request $request)
    {
        $query = PenjadwalanHari::with([

            'jam',
            'jadwal.tahun',
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.guru'
        ]);

        if ($request->id_tahun) {

            $query->whereHas('jadwal', function ($q) use ($request) {

                $q->where('id_tahun', $request->id_tahun);
            });
        }

        if ($request->id_kelas) {

            $query->whereHas('jadwal', function ($q) use ($request) {

                $q->where('id_kelas', $request->id_kelas);
            });
        }

        if ($request->id_jurusan) {

            $query->whereHas('jadwal.kelas', function ($q) use ($request) {

                $q->where('id_jurusan', $request->id_jurusan);
            });
        }

        if ($request->filled('mapel')) {
            $query->whereHas('jadwal.mapel', function ($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->mapel . '%');
            });
}

        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        $data = $query
            ->orderBy('id', 'desc')
            ->selectRaw("
        tb_penjadwalan_hari.*,
        DAYNAME((19900100 + tb_penjadwalan_hari.id_hari)) as hari
        ")
            ->get()
            ->map(function ($item) {

                return [

                    'id'              => $item->id,
                    'idpenjadwalan'   => $item->idpenjadwalan,
                    'id_hari'         => $item->id_hari,
                    'hari'            => $hari[$item->id_hari] ?? '',
                    'jam_ke'          => $item->jam->jam_ke ?? '',
                    'jam_awal'        => $item->jam->jam_awal ?? '',
                    'jam_akhir'       => $item->jam->jam_akhir ?? '',

                        'id_jam'          => $item->id_jam,

                    'tahun_ajaran'    => $item->jadwal->tahun->thn_ajaran ?? '',
                    'semester'        => $item->jadwal->semester ?? '',
                    'angkatan'        => $item->jadwal->angkatan ?? '',

                    'nama_kelas'      => $item->jadwal->kelas->nama_kelas ?? '',
                    'nama_mapel'      => $item->jadwal->mapel->nama_mapel ?? '',
                    'nama_gtk'        => $item->jadwal->guru->nama_gtk ?? '',

                    'jml_jam'         => $item->jadwal->jml_jam ?? '',
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $jadwal = MasterJadwal::create([
            'id_tahun' => $request->id_tahun,
            'semester' => $request->semester,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'jml_jam'  => $request->jml_jam,
            'id_gtk'   => $request->id_gtk,
            'angkatan' => $request->angkatan,
        ]);

        PenjadwalanHari::create([
            'idpenjadwalan' => $jadwal->id
        ]);

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'create',
            'user'        => auth()->user()->id,
            'ip'          => $request->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/store')
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil disimpan'
        ]);
    }

    public function update(Request $request, $id)
    {
        $jadwal = MasterJadwal::findOrFail($id);

        $jadwal->update([
            'id_tahun' => $request->id_tahun,
            'semester' => $request->semester,
            'id_kelas' => $request->id_kelas,
            'id_mapel' => $request->id_mapel,
            'jml_jam'  => $request->jml_jam,
            'id_gtk'   => $request->id_gtk,
            'angkatan' => $request->angkatan,
        ]);

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'update',
            'user'        => auth()->user()->id,
            'ip'          => $request->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/update/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil diupdate'
        ]);
    }

    public function destroy($id)
    {
        $jadwal = MasterJadwal::findOrFail($id);

        $jadwal->delete();

        LogModel::create([
            'tanggal'     => now(),
            'tabel'       => 'tb_master_jadwal',
            'aksi'        => 'delete',
            'user'        => auth()->user()->id,
            'ip'          => request()->ip(),
            'keterangan'  => json_encode($jadwal),
            'serial'      => url('master-jadwal/delete/' . $id)
        ]);

        return response()->json([
            'success' => true,
            'msg'     => 'Data berhasil dihapus'
        ]);
    }

    public function updateGuru(Request $request)
    {
        foreach ($request->data as $row) {
            MasterJadwal::where('id', $row['id'])
                ->update([
                    'id_gtk' => $row['id_gtk']
                ]);
        }

        return response()->json([
            'success' => true,
            'msg' => 'Data guru berhasil diperbarui'
        ]);
    }

    public function inisialisasi(Request $request)
    {
        $thn      = $request->thn;
        $smt      = $request->smt;       
        $jurusan  = $request->jurusan;

        if ($smt == 1) {

            $kolomSemester = [
                'tb_mapel.smt1',
                'tb_mapel.smt3',
                'tb_mapel.smt5'
            ];
        } else {

            $kolomSemester = [
                'tb_mapel.smt2',
                'tb_mapel.smt4',
                'tb_mapel.smt6'
            ];
        }

        $berhasil = 0;
        $gagal    = 0;

        DB::beginTransaction();

        try {

            // Hapus jadwal lama
           $ids = DB::table('tb_master_jadwal')
                ->where('id_tahun', $thn)
                ->where('semester', $smt)
                ->whereIn(
                    'id_kelas',
                    DB::table('tb_kelas')
                        ->select('idx')
                        ->where('id_jurusan', $jurusan)
                )
                ->pluck('id');

            DB::table('tb_penjadwalan_hari')
                ->whereIn('idpenjadwalan', $ids)
                ->delete();

            DB::table('tb_master_jadwal')
                ->whereIn('id', $ids)
                ->delete();

            $angkatan = $thn;

            foreach ($kolomSemester as $kolom) {

                $mapel = DB::table('tb_mapel')
                    ->leftJoin(
                        'tb_kelas',
                        'tb_mapel.id_jurusan',
                        '=',
                        'tb_kelas.id_jurusan'
                    )
                    ->selectRaw("
                        tb_mapel.id,
                        tb_mapel.nama_mapel,
                        tb_mapel.id_kategori_mapel,
                        tb_kelas.idx,
                        {$kolom} as jml_jam
                    ")
                    ->where('tb_mapel.id_jurusan', $jurusan)
                    ->whereRaw("{$kolom} > 0")
                    ->orderBy('id_kategori_mapel')
                    ->orderBy('tb_mapel.id')
                    ->get();

                foreach ($mapel as $row) {

                    $jadwal = MasterJadwal::create([
                        'id_tahun' => $thn,
                        'semester' => $smt,
                        'angkatan' => $angkatan,
                        'id_kelas' => $row->idx,
                        'id_mapel' => $row->id,
                        'jml_jam'  => $row->jml_jam,
                    ]);

                    if ($jadwal) {

                        $berhasil++;

                        for ($x = 0; $x < $row->jml_jam; $x++) {

                            PenjadwalanHari::create([
                                'idpenjadwalan' => $jadwal->id
                            ]);
                        }
                    } else {

                        $gagal++;
                    }
                }

                $angkatan--;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'title'   => 'Sukses',
                'msg'     => "Inisialisasi data (B {$berhasil}, S {$gagal})"
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

    public function updateDetail(Request $request)
    {
        DB::beginTransaction();

        try {

            foreach ($request->data as $row) {

                PenjadwalanHari::where('id', $row['id'])
                    ->update([
                        'id_hari' => $row['id_hari'],
                        'id_jam'  => $row['id_jam'] ?? 0,
                    ]);

               
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'msg' => 'Detail jadwal berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ], 500);
        }
    }



    public function isiNilai(Request $request)
    {
        $thn      = $request->thn;
        $smt      = $request->smt;
        $jurusan  = $request->jurusan;
        $kelas    = $request->kelas;
        $angkatan = $request->angkatan;

        DB::beginTransaction();

        try {

            if (!empty($kelas)) {

                DB::statement("
                DELETE FROM tb_nilai
                WHERE idjadwal IN (
                    SELECT id
                    FROM tb_master_jadwal
                    WHERE id_tahun = ?
                    AND semester = ?
                    AND id_kelas = ?
                    AND angkatan = ?
                )
            ", [
                    $thn,
                    $smt,
                    $kelas,
                    $angkatan
                ]);

                DB::statement("
                INSERT INTO tb_nilai (
                    idjadwal,
                    nipd
                )
                SELECT
                    tb_master_jadwal.id,
                    tb_siswa.nipd
                FROM tb_master_jadwal
                INNER JOIN tb_siswa
                    ON tb_master_jadwal.id_kelas = tb_siswa.id_kelas
                    AND tb_master_jadwal.angkatan = tb_siswa.id_thn_ajaran
                WHERE tb_master_jadwal.id_tahun = ?
                AND tb_master_jadwal.semester = ?
                AND tb_siswa.id_thn_ajaran = ?
                AND tb_master_jadwal.id_kelas = ?
            ", [
                    $thn,
                    $smt,
                    $angkatan,
                    $kelas
                ]);
            } elseif (!empty($jurusan)) {

                DB::statement("
                DELETE FROM tb_nilai
                WHERE idjadwal IN (
                    SELECT id
                    FROM tb_master_jadwal
                    WHERE id_tahun = ?
                    AND semester = ?
                    AND angkatan = ?
                    AND id_kelas IN (
                        SELECT idx
                        FROM tb_kelas
                        WHERE id_jurusan = ?
                    )
                )
            ", [
                    $thn,
                    $smt,
                    $angkatan,
                    $jurusan
                ]);

                DB::statement("
                INSERT INTO tb_nilai (
                    idjadwal,
                    nipd
                )
                SELECT
                    tb_master_jadwal.id,
                    tb_siswa.nipd
                FROM tb_master_jadwal
                INNER JOIN tb_siswa
                    ON tb_master_jadwal.id_kelas = tb_siswa.id_kelas
                    AND tb_master_jadwal.angkatan = tb_siswa.id_thn_ajaran
                WHERE tb_master_jadwal.id_tahun = ?
                AND tb_master_jadwal.semester = ?
                AND tb_siswa.id_thn_ajaran = ?
                AND tb_master_jadwal.id_kelas IN (
                    SELECT idx
                    FROM tb_kelas
                    WHERE id_jurusan = ?
                )
            ", [
                    $thn,
                    $smt,
                    $angkatan,
                    $jurusan
                ]);
            } else {

                return response()->json([
                    'success' => false,
                    'title'   => 'Peringatan',
                    'msg'     => 'Pilih kelas atau jurusan terlebih dahulu'
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'title'   => 'Success',
                'msg'     => 'Proses isi nilai berhasil dilakukan'
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
}
