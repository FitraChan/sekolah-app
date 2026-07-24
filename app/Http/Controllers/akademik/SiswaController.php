<?php

namespace App\Http\Controllers\akademik;

use App\Http\Controllers\Controller;
use App\Models\Agama;
use App\Models\Gelombang;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\LogModel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class SiswaController extends Controller
{
    public function index()
    {
        return view('akademik.siswa.index', [
            'side' => 'siswa',

            'jurusan' => Jurusan::query()
                ->orderBy('nama_jurusan')
                ->get(),

            'kelas' => Kelas::query()
                ->orderBy('idx')
                ->get(),

            'agama' => Agama::query()
                ->orderBy('nama_agama')
                ->get(),

            'gelombang' => Gelombang::query()
                ->orderBy('id')
                ->get(),

            'tahunAjaran' => TahunAjaran::query()
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function data(Request $request)
    {
        $query = Siswa::query()
            ->with([
                'jurusan',
                'kelas',
                'agama',
                'gelombang',
                'tahunAjaran',
            ]);

        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->id_jurusan);
        }

        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->id_kelas);
        }

        if ($request->filled('id_thn_ajaran')) {
            $query->where('id_thn_ajaran', $request->id_thn_ajaran);
        }

        if ($request->filled('is_aktif')) {
            $query->where('is_aktif', $request->boolean('is_aktif'));
        }

        $data = $query
            ->orderBy('nama_lengkap')
            ->get()
            ->map(function (Siswa $item) {
                return [
                    'id'                  => $item->id,
                    'id_cawa'             => $item->id_cawa,
                    'no_daftar'           => $item->no_daftar,
                    'nipd'                => $item->nipd,
                    'no_registrasi_ulang' => $item->no_registrasi_ulang,
                    'nama_lengkap'        => $item->nama_lengkap,
                    'nama_panggilan'      => $item->nama_panggilan,
                    'jk'                  => $item->jk,
                    'jenis_kelamin'       => $this->jenisKelamin($item->jk),
                    'nisn'                => $item->nisn,
                    'nik'                 => $item->nik,
                    'tmp_lahir'           => $item->tmp_lahir,
                    'tgl_lahir'           => optional($item->tgl_lahir)
                        ->format('Y-m-d'),

                    'id_agama'            => $item->id_agama,
                    'agama'               => $item->agama?->nama_agama,

                    'alamat'              => $item->alamat,
                    'desa'                => $item->desa,
                    'kecamatan'           => $item->kecamatan,
                    'kota'                => $item->kota,
                    'provinsi'            => $item->provinsi,
                    'no_hp'               => $item->no_hp,
                    'email'               => $item->email,

                    'nama_ayah'           => $item->nama_ayah,
                    'nama_ibu'            => $item->nama_ibu,
                    'nama_wali'           => $item->nama_wali,

                    'tgl_masuk'           => optional($item->tgl_masuk)
                        ->format('Y-m-d'),

                    'id_jurusan'          => $item->id_jurusan,
                    'jurusan'             => $item->jurusan?->nama_jurusan,

                    'id_kelas'            => $item->id_kelas,
                    'kelas'               => $item->kelas?->nama_kelas,

                    'id_gelombang'        => $item->id_gelombang,
                    'gelombang'           => $item->gelombang?->nama_gelombang,

                    'id_thn_ajaran'       => $item->id_thn_ajaran,
                    'tahun_ajaran'        => $item->tahunAjaran?->thn_ajaran,

                    'jns_kelas'           => $item->jns_kelas,
                    'jenis_kelas'         => $item->jenis_kelas,

                    'nama_sekolah_asal'   => $item->nama_sekolah_asal,
                    'image'               => $item->image,
                    'sts_siswa'           => $item->sts_siswa,
                    'is_aktif'            => (bool) $item->is_aktif,
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],

            'nama_panggilan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nipd' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tb_siswa', 'nipd'),
            ],

            'nisn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('tb_siswa', 'nisn'),
            ],

            'nik' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('tb_siswa', 'nik'),
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('tb_siswa', 'email'),
            ],

            'jk'              => ['required'],
            'tmp_lahir'       => ['nullable', 'string', 'max:100'],
            'tgl_lahir'       => ['nullable', 'date'],
            'id_agama'        => ['nullable', 'integer'],
            'id_jurusan'      => ['required', 'integer'],
            'id_kelas'        => ['required', 'integer'],
            'id_gelombang'    => ['nullable', 'integer'],
            'id_thn_ajaran'   => ['required', 'integer'],
            'jns_kelas'       => ['nullable', 'integer', 'in:1,2'],
            'no_hp'           => ['nullable', 'string', 'max:30'],
            'alamat'          => ['nullable', 'string'],
            'nama_ayah'       => ['nullable', 'string', 'max:150'],
            'nama_ibu'        => ['nullable', 'string', 'max:150'],
            'nama_wali'       => ['nullable', 'string', 'max:150'],
            'nama_sekolah_asal' => ['nullable', 'string', 'max:150'],
            'tgl_masuk'       => ['nullable', 'date'],
            'password'        => ['required', 'string', 'min:6'],
            'is_aktif'        => ['nullable', 'boolean'],
        ]);

        try {
            $kelas = DB::transaction(function () use ($request, $validated) {
                $data = $this->dataSiswa($request, $validated);

                $data['password'] = Hash::make($validated['password']);
                $data['is_aktif'] = $request->boolean('is_aktif', true);
                $data['id_petugas'] = auth()->id();

                $siswa = Siswa::create($data);

                $this->simpanLog(
                    request: $request,
                    aksi: 'create',
                    siswa: $siswa,
                    serial: url('siswa/store')
                );

                return $siswa;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil disimpan.',
                'data'    => $kelas,
            ], 201);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Data siswa gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],

            'nama_panggilan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'nipd' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tb_siswa', 'nipd')->ignore($siswa->id),
            ],

            'nisn' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('tb_siswa', 'nisn')->ignore($siswa->id),
            ],

            'nik' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('tb_siswa', 'nik')->ignore($siswa->id),
            ],

            'email' => [
                'nullable',
                'email',
                'max:150',
                Rule::unique('tb_siswa', 'email')->ignore($siswa->id),
            ],

            'jk'                => ['required'],
            'tmp_lahir'         => ['nullable', 'string', 'max:100'],
            'tgl_lahir'         => ['nullable', 'date'],
            'id_agama'          => ['nullable', 'integer'],
            'id_jurusan'        => ['required', 'integer'],
            'id_kelas'          => ['required', 'integer'],
            'id_gelombang'      => ['nullable', 'integer'],
            'id_thn_ajaran'     => ['required', 'integer'],
            'jns_kelas'         => ['nullable', 'integer', 'in:1,2'],
            'no_hp'             => ['nullable', 'string', 'max:30'],
            'alamat'            => ['nullable', 'string'],
            'nama_ayah'         => ['nullable', 'string', 'max:150'],
            'nama_ibu'          => ['nullable', 'string', 'max:150'],
            'nama_wali'         => ['nullable', 'string', 'max:150'],
            'nama_sekolah_asal' => ['nullable', 'string', 'max:150'],
            'tgl_masuk'         => ['nullable', 'date'],
            'password'          => ['nullable', 'string', 'min:6'],
            'is_aktif'          => ['nullable', 'boolean'],
        ]);

        try {
            DB::transaction(function () use (
                $request,
                $validated,
                $siswa
            ) {
                $dataLama = $siswa->getOriginal();

                $data = $this->dataSiswa($request, $validated);

                $data['is_aktif'] = $request->boolean(
                    'is_aktif',
                    $siswa->is_aktif
                );

                if ($request->filled('password')) {
                    $data['password'] = Hash::make(
                        $validated['password']
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
                        'sesudah' => $siswa->fresh()->toArray(),
                    ]),
                    'serial' => url('siswa/update/' . $siswa->id),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Data siswa gagal diperbarui.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);

        try {
            DB::transaction(function () use ($siswa) {
                $dataSiswa = $siswa->toArray();

                $siswa->delete();

                LogModel::create([
                    'tanggal' => now(),
                    'tabel' => 'tb_siswa',
                    'aksi' => 'delete',
                    'user' => auth()->id(),
                    'ip' => request()->ip(),
                    'keterangan' => json_encode($dataSiswa),
                    'serial' => url('siswa/delete/' . $siswa->id),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Data siswa gagal dihapus.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    private function dataSiswa(
        Request $request,
        array $validated
    ): array {
        return [
            'id_cawa' => $request->id_cawa,
            'no_daftar' => $request->no_daftar,
            'nipd' => $validated['nipd'],
            'no_registrasi_ulang' => $request->no_registrasi_ulang,
            'no_kwitansi' => $request->no_kwitansi,
            'tmp_daftar' => $request->tmp_daftar,

            'nama_lengkap' => $validated['nama_lengkap'],
            'nama_panggilan' => $validated['nama_panggilan'] ?? null,
            'jk' => $validated['jk'],
            'nisn' => $validated['nisn'] ?? null,
            'nik' => $validated['nik'] ?? null,

            'tmp_lahir' => $validated['tmp_lahir'] ?? null,
            'tgl_lahir' => $validated['tgl_lahir'] ?? null,
            'id_agama' => $validated['id_agama'] ?? null,

            'alamat' => $validated['alamat'] ?? null,
            'desa' => $request->desa,
            'kecamatan' => $request->kecamatan,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,

            'no_hp' => $validated['no_hp'] ?? null,
            'email' => $validated['email'] ?? null,

            'nama_ayah' => $validated['nama_ayah'] ?? null,
            'nama_ibu' => $validated['nama_ibu'] ?? null,
            'nama_wali' => $validated['nama_wali'] ?? null,

            'tgl_masuk' => $validated['tgl_masuk'] ?? null,
            'id_jurusan' => $validated['id_jurusan'],
            'nama_sekolah_asal' =>
                $validated['nama_sekolah_asal'] ?? null,

            'tgl_registrasi' => $request->tgl_registrasi,
            'id_template_bayar' => $request->id_template_bayar,
            'id_kelas' => $validated['id_kelas'],
            'kelas_id' => $request->kelas_id,
            'id_gelombang' => $validated['id_gelombang'] ?? null,
            'jns_kelas' => $validated['jns_kelas'] ?? 1,

            'image' => $request->image,
            'id_periode' => $request->id_periode,
            'sts_siswa' => $request->sts_siswa ?? 1,
            'id_user' => $request->id_user,
            'id_thn_ajaran' => $validated['id_thn_ajaran'],
        ];
    }

    private function simpanLog(
        Request $request,
        string $aksi,
        Siswa $siswa,
        string $serial
    ): void {
        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_siswa',
            'aksi' => $aksi,
            'user' => auth()->id(),
            'ip' => $request->ip(),
            'keterangan' => json_encode($siswa),
            'serial' => $serial,
        ]);
    }

    private function jenisKelamin($jk): string
    {
        return match ((string) $jk) {
            'L', '1' => 'Laki-laki',
            'P', '2' => 'Perempuan',
            default => '-',
        };
    }
}