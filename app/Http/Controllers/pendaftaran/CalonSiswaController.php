<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\CalonSiswa;
use App\Models\Gelombang;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Agama;
use App\Models\Pekerjaan;
use App\Models\StatusDaftar;
use Illuminate\Http\Request;
use PHPUnit\Logging\OpenTestReporting\Status;

class CalonSiswaController extends Controller
{
    public function index()
    {
        $side = 'calon-siswa';

        $gelombang = Gelombang::all();

        $jurusan = Jurusan::all();

        return view('pendaftaran.calon_siswa.index', compact(
            'side',
            'gelombang',
            'jurusan'
        ), ['side'  => 'calon-siswa']);

        
    }

     

    public function create()
    {
        $side = 'calon-siswa';

            $rows = new CalonSiswa();

       // $rows = CalonSiswa::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        $gel = Gelombang::orderBy('idx', 'asc')->get();

        $thn = TahunAjaran::orderBy('id', 'desc')->get();

        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();

        $sts_daftar = StatusDaftar::orderBy('keterangan', 'asc')->get();

        // $petugas = User::orderBy('name', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $stsdaftar =
            'Belum Ada';

        return view(
            'pendaftaran.calon_siswa.edit_calon_siswa',
            compact(
                'side',
                'rows',
                'gel',
                'thn',
                'lists',
                'jobs',
                'agama',
                'sts_daftar',
                // 'petugas',
                'stsdaftar'
            )
        );
    }

    public function edit($id)
    {
        $side = 'calon-siswa';

        $rows = CalonSiswa::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        $gel = Gelombang::orderBy('idx', 'asc')->get();

        $thn = TahunAjaran::orderBy('id', 'desc')->get();

        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();

        $sts_daftar = StatusDaftar::orderBy('keterangan', 'asc')->get();

        // $petugas = User::orderBy('name', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        $stsdaftar =
            $rows->statusDaftar->keterangan
            ?? 'Belum Ada';

        return view(
            'pendaftaran.calon_siswa.edit_calon_siswa',
            compact(
                'side',
                'rows',
                'gel',
                'thn',
                'lists',
                'jobs',
                'agama',
                'sts_daftar',
                // 'petugas',
                'stsdaftar'
            )
        );
    }

    public function daftarSiswa()    {  
        
     $data = CalonSiswa::with([
        'tahunAjaran',
        'jurusan',
        'kelas'
    ])

    ->whereHas('tahunAjaran', function ($query) {

        $query->where('isaktiv', 1);

    })

    ->get();

         return view('pendaftaran.calon_siswa.daftar_siswa', compact(            
            'data'
        ),[
            'side' => 'daftar-siswa'
        ]);
    }

    public function data()
    {
        $data = CalonSiswa::with([
            'gelombang',
            'jurusan'
        ])
            ->latest('id')
            ->get()
            ->map(function ($item) {

                return [

                    'id' => $item->id,

                    'nama_lengkap' => $item->nama_lengkap,

                    'jk' => $item->jk,

                    'nisn' => $item->nisn,

                    'no_hp' => $item->no_hp,

                    'nama_jurusan' =>
                    $item->jurusan->nama_jurusan ?? '-',

                    'nama_gelombang' =>
                    $item->gelombang->nama_gelombang ?? '-',

                    'id_jurusan' => $item->id_jurusan,

                    'id_gelombang' => $item->id_gelombang,
                ];
            });

        return response()->json($data);
    }

    public function store(Request $request)
    {
        CalonSiswa::create($request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function updateRegistrasiSiswa(Request $request, $id = null)
{
    try {

        $request->validate([

            /*
            |--------------------------------------------------------------------------
            | INFORMASI PENDAFTARAN
            |--------------------------------------------------------------------------
            */

            'id_gelombang'      => 'nullable',
            'id_thn_ajaran'     => 'nullable',
            'id_jurusan'        => 'nullable',
            'no_daftar'         => 'nullable|string|max:100',
            'tgl_daftar'        => 'nullable|date',
            'tmp_daftar'        => 'nullable|string|max:255',
            'status_daftar'     => 'nullable',

            /*
            |--------------------------------------------------------------------------
            | BIODATA SISWA
            |--------------------------------------------------------------------------
            */

            'nama_lengkap'      => 'nullable|string|max:255',
            'jk'                => 'nullable|string|max:1',
            'id_agama'          => 'nullable',
            'tmp_lahir'         => 'nullable|string|max:255',
            'tgl_lahir'         => 'nullable|date',
            'alamat'            => 'nullable|string',
            'dusun'             => 'nullable|string|max:255',
            'desa'              => 'nullable|string|max:255',
            'kecamatan'         => 'nullable|string|max:255',
            'kota'              => 'nullable|string|max:255',
            'provinsi'          => 'nullable|string|max:255',
            'no_hp'             => 'nullable|string|max:20',
            'no_telp'           => 'nullable|string|max:20',

        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK EDIT / TAMBAH
        |--------------------------------------------------------------------------
        */

        if (!empty($id)) {
            // UPDATE
            $siswa = CalonSiswa::findOrFail($id);

        } else {

            // TAMBAH
            $siswa = new CalonSiswa();
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA
        |--------------------------------------------------------------------------
        */

        $siswa->id_gelombang      = $request->id_gelombang;
        $siswa->id_thn_ajaran     = $request->id_thn_ajaran;
        $siswa->id_jurusan        = $request->id_jurusan;
        $siswa->no_daftar         = $request->no_daftar;
        $siswa->tgl_daftar        = $request->tgl_daftar;
        $siswa->tmp_daftar        = $request->tmp_daftar;
        $siswa->status_daftar     = $request->status_daftar;

        $siswa->nama_lengkap      = $request->nama_lengkap;
        $siswa->jk                = $request->jk;
        $siswa->id_agama          = $request->id_agama;
        $siswa->tmp_lahir         = $request->tmp_lahir;
        $siswa->tgl_lahir         = $request->tgl_lahir;
        $siswa->alamat            = $request->alamat;
        $siswa->dusun             = $request->dusun;
        $siswa->desa              = $request->desa;
        $siswa->kecamatan         = $request->kecamatan;
        $siswa->kota              = $request->kota;
        $siswa->provinsi          = $request->provinsi;
        $siswa->no_hp             = $request->no_hp;
        $siswa->no_telp           = $request->no_telp;

        $siswa->save();

        /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($id != null) {

            $message = 'Data registrasi & biodata siswa berhasil diperbarui';

        } else {

            $message = 'Data registrasi & biodata siswa berhasil ditambahkan';
        }

        return redirect()
            ->route('calon-siswa.index')
            ->with('success', $message);

    } catch (\Exception $e) {

print_r($e->getMessage());
        // return redirect()
        //     ->back()
        //     ->withInput()
        //     ->with('error', 'Terjadi kesalahan saat menyimpan data');
    }
}

    public function updateOrangTua(Request $request, $id)
    {
        try {

            $request->validate([
                'nama_ayah'             => 'nullable|string|max:255',
                'pekerjaan_ayah'        => 'nullable',
                'alamat_ayah'           => 'nullable|string',
                'hp_ayah'               => 'nullable|string|max:20',
                //'tahu_smk_dari_mana'    => 'nullable|string|max:255',
            ]);

            $siswa = CalonSiswa::findOrFail($id);

            $siswa->update([
                'nama_ayah'             => $request->nama_ayah,
                'id_kerja_ayah'         => $request->pekerjaan_ayah,
                'alamat_ayah'           => $request->alamat_ayah,
                'hp_ayah'               => $request->hp_ayah,
                //'tahu_smk_dari_mana'    => $request->tahu_smk_dari_mana,
            ]);

            return back()->with('success', 'Data orang tua berhasil diupdate');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateRegistrasi(Request $request, $id)
    {
        try {

            $request->validate([
                'sekolah_asal'              => 'nullable|string|max:255',
                'alamat_sekolah_asal'       => 'nullable|string',
                'kabupaten_sekolah_asal'    => 'nullable|string|max:255',
                'provinsi_sekolah_asal'     => 'nullable|string|max:255',
            ]);

            $siswa = CalonSiswa::findOrFail($id);

            $siswa->update([
                'nama_sekolah_asal'     => $request->sekolah_asal,
                'alamat_sekolah_asal'   => $request->alamat_sekolah_asal,
                'kab_sekolah'           => $request->kabupaten_sekolah_asal,
                'prov_sekolah'          => $request->provinsi_sekolah_asal,
            ]);

            return back()->with('success', 'Data sekolah berhasil diupdate');
        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
{
    try {

        $request->validate([
            'status_daftar' => 'required',
        ]);

        $siswa = CalonSiswa::findOrFail($id);

        $siswa->update([
            'status_daftar' => $request->status_daftar,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Status siswa berhasil diperbarui');

    } catch (\Exception $e) {


        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat update status');
    }
}

    public function destroy($id)
    {
        CalonSiswa::findOrFail($id)->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
