<?php

namespace App\Http\Controllers\siswa;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\LogModel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index()
    {
        $side = 'siswa';

        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')
            ->get();

        $kelas = Kelas::orderBy('idx', 'asc')
            ->get();

        $tahunAjaran = TahunAjaran::orderBy('id', 'desc')
            ->get();

        return view(
            'akademik.siswa.index',
            compact(
                'side',
                'jurusan',
                'kelas',
                'tahunAjaran'
            )
        );
    }

    public function create()
    {
        $side = 'siswa';

        $rows = new Siswa();

        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')
            ->get();

        $kelas = Kelas::orderBy('idx', 'asc')
            ->get();

        $agama = Agama::orderBy('nama_agama', 'asc')
            ->get();

        $tahunAjaran = TahunAjaran::orderBy('id', 'desc')
            ->get();

        return view(
            'akademik.siswa.edit_siswa',
            compact(
                'side',
                'rows',
                'jurusan',
                'kelas',
                'agama',
                'tahunAjaran'
            )
        );
    }

    public function edit($id)
    {
        $side = 'siswa';

        $rows = Siswa::findOrFail($id);

        $jurusan = Jurusan::orderBy('nama_jurusan', 'asc')
            ->get();

        $kelas = Kelas::orderBy('idx', 'asc')
            ->get();

        $agama = Agama::orderBy('nama_agama', 'asc')
            ->get();

        $tahunAjaran = TahunAjaran::orderBy('id', 'desc')
            ->get();

        return view(
            'akademik.siswa.edit_siswa',
            compact(
                'side',
                'rows',
                'jurusan',
                'kelas',
                'agama',
                'tahunAjaran'
            )
        );
    }

    public function data(Request $request)
    {
        $query = Siswa::with([
            'jurusan',
            'kelas',
            'agama',
            'tahunAjaran',
        ]);

        if ($request->filled('id_jurusan')) {
            $query->where(
                'id_jurusan',
                $request->id_jurusan
            );
        }

        if ($request->filled('id_kelas')) {
            $query->where(
                'id_kelas',
                $request->id_kelas
            );
        }

        if ($request->filled('id_thn_ajaran')) {
            $query->where(
                'id_thn_ajaran',
                $request->id_thn_ajaran
            );
        }

        if ($request->has('is_aktif') &&
            $request->is_aktif !== '') {
            $query->where(
                'is_aktif',
                $request->is_aktif
            );
        }

        $data = $query
            ->latest('id')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,

                    'nipd' => $item->nipd,

                    'nama_lengkap' =>
                    $item->nama_lengkap,

                    'nama_panggilan' =>
                    $item->nama_panggilan,

                    'jk' => $item->jk,

                    'jenis_kelamin' =>
                    $item->jk === 'L'
                        ? 'Laki-laki'
                        : ($item->jk === 'P'
                            ? 'Perempuan'
                            : '-'),

                    'nisn' => $item->nisn,
                    'nik' => $item->nik,
                    'no_hp' => $item->no_hp,
                    'email' => $item->email,

                    'tmp_lahir' =>
                    $item->tmp_lahir,

                    'tgl_lahir' =>
                    $item->tgl_lahir
                        ? $item->tgl_lahir
                            ->format('Y-m-d')
                        : null,

                    'id_agama' =>
                    $item->id_agama,

                    'nama_agama' =>
                    $item->agama?->nama_agama ?? '-',

                    'id_jurusan' =>
                    $item->id_jurusan,

                    'nama_jurusan' =>
                    $item->jurusan?->nama_jurusan ?? '-',

                    'id_kelas' =>
                    $item->id_kelas,

                    'nama_kelas' =>
                    $item->kelas?->nama_kelas ?? '-',

                    'id_thn_ajaran' =>
                    $item->id_thn_ajaran,

                    'tahun_ajaran' =>
                    $item->tahunAjaran?->thn_ajaran ?? '-',

                    'jns_kelas' =>
                    $item->jns_kelas,

                    'jenis_kelas' =>
                    $item->jenis_kelas,

                    'nama_ayah' =>
                    $item->nama_ayah,

                    'nama_ibu' =>
                    $item->nama_ibu,

                    'nama_wali' =>
                    $item->nama_wali,

                    'alamat' =>
                    $item->alamat,

                    'is_aktif' =>
                    $item->is_aktif,

                    'sts_siswa' =>
                    $item->sts_siswa,
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' =>
            'required|string|max:255',

            'nipd' =>
            'required|string|max:50|unique:tb_siswa,nipd',

            'nisn' =>
            'nullable|string|max:30|unique:tb_siswa,nisn',

            'email' =>
            'nullable|email|max:255|unique:tb_siswa,email',

            'jk' =>
            'required|in:L,P',

            'id_jurusan' =>
            'required',

            'id_kelas' =>
            'required',

            'id_thn_ajaran' =>
            'required',

            'password' =>
            'nullable|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->only([
                'id_cawa',
                'no_daftar',
                'nipd',
                'no_registrasi_ulang',
                'no_kwitansi',
                'tmp_daftar',
                'nama_lengkap',
                'nama_panggilan',
                'jk',
                'nisn',
                'nik',
                'tmp_lahir',
                'tgl_lahir',
                'id_agama',
                'alamat',
                'desa',
                'kecamatan',
                'kota',
                'provinsi',
                'no_hp',
                'email',
                'nama_ayah',
                'nama_ibu',
                'nama_wali',
                'tgl_masuk',
                'id_jurusan',
                'nama_sekolah_asal',
                'tgl_registrasi',
                'id_template_bayar',
                'id_kelas',
                'kelas_id',
                'id_gelombang',
                'jns_kelas',
                'image',
                'id_periode',
                'sts_siswa',
                'id_user',
                'id_thn_ajaran',
                'is_aktif',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make(
                    $request->password
                );
            } else {
                /*
                 * Password awal menggunakan NIPD.
                 */
                $data['password'] = Hash::make(
                    $request->nipd
                );
            }

            $data['id_petugas'] = auth()->id();

            $data['is_aktif'] =
                $request->has('is_aktif')
                    ? $request->is_aktif
                    : 1;

            $data['sts_siswa'] =
                $request->sts_siswa ?? 1;

            $siswa = Siswa::create($data);

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_siswa',
                'aksi' => 'create',
                'user' => auth()->id(),
                'ip' => $request->ip(),
                'keterangan' =>
                json_encode($siswa),
                'serial' => url('siswa/store'),
            ]);

            DB::commit();

            return redirect()
                ->route('siswa.index')
                ->with(
                    'success',
                    'Data siswa berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function update(
        Request $request,
        $id
    ) {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama_lengkap' =>
            'required|string|max:255',

            'nipd' =>
            'required|string|max:50|unique:tb_siswa,nipd,' .
                $siswa->id,

            'nisn' =>
            'nullable|string|max:30|unique:tb_siswa,nisn,' .
                $siswa->id,

            'email' =>
            'nullable|email|max:255|unique:tb_siswa,email,' .
                $siswa->id,

            'jk' =>
            'required|in:L,P',

            'id_jurusan' =>
            'required',

            'id_kelas' =>
            'required',

            'id_thn_ajaran' =>
            'required',

            'password' =>
            'nullable|string|min:6',
        ]);

        DB::beginTransaction();

        try {
            $dataLama = $siswa->toArray();

            $data = $request->only([
                'id_cawa',
                'no_daftar',
                'nipd',
                'no_registrasi_ulang',
                'no_kwitansi',
                'tmp_daftar',
                'nama_lengkap',
                'nama_panggilan',
                'jk',
                'nisn',
                'nik',
                'tmp_lahir',
                'tgl_lahir',
                'id_agama',
                'alamat',
                'desa',
                'kecamatan',
                'kota',
                'provinsi',
                'no_hp',
                'email',
                'nama_ayah',
                'nama_ibu',
                'nama_wali',
                'tgl_masuk',
                'id_jurusan',
                'nama_sekolah_asal',
                'tgl_registrasi',
                'id_template_bayar',
                'id_kelas',
                'kelas_id',
                'id_gelombang',
                'jns_kelas',
                'image',
                'id_periode',
                'sts_siswa',
                'id_user',
                'id_thn_ajaran',
                'is_aktif',
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make(
                    $request->password
                );
            }

            $siswa->update($data);

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_siswa',
                'aksi' => 'update',
                'user' => auth()->id(),
                'ip' => $request->ip(),
                'keterangan' => json_encode([
                    'sebelum' => $dataLama,
                    'sesudah' => $siswa
                        ->fresh()
                        ->toArray(),
                ]),
                'serial' =>
                url('siswa/update/' . $id),
            ]);

            DB::commit();

            return redirect()
                ->route('siswa.index')
                ->with(
                    'success',
                    'Data siswa berhasil diperbarui.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $siswa = Siswa::findOrFail($id);

            $dataSiswa = $siswa->toArray();

            $siswa->delete();

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_siswa',
                'aksi' => 'delete',
                'user' => auth()->id(),
                'ip' => request()->ip(),
                'keterangan' =>
                json_encode($dataSiswa),
                'serial' =>
                url('siswa/delete/' . $id),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' =>
                'Data siswa berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}