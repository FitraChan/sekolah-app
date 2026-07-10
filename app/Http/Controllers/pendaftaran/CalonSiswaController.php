<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\CalonSiswa;
use App\Models\Gelombang;
use App\Models\Jurusan;
use App\Models\TahunAjaran;
use App\Models\Agama;
use App\Models\ItemBayar;
use App\Models\Pekerjaan;
use App\Models\StatusDaftar;
use App\Models\IpaymuBayar;
use App\Models\IpaymuDetBayar;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\BayarCalonSiswa;
use App\Models\DetBayarCalonSiswa;




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

         $itemBayar = ItemBayar::where('id_kategori', 2)
            ->get();

        $thn = TahunAjaran::orderBy('id', 'desc')->get();

        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();

        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();

        $sts_daftar = StatusDaftar::orderBy('keterangan', 'asc')->get();

        $bukti = CalonSiswa::with('buktiPembayaran')
            ->where('id_user', Auth::id())
            ->get();

        // $petugas = User::orderBy('name', 'asc')->get();

        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */
        $dataIpaymu = '';

        $stsdaftar =
            'Belum Ada';

        return view(
            'pendaftaran.calon_siswa.edit_calon_siswa',
            compact(
                'side',
                'rows',
                'dataIpaymu',
                'gel',
                'thn',
                'lists',
                'jobs',
                'agama',
                'sts_daftar',
                // 'petugas',
                'stsdaftar',
                'bukti',
                'itemBayar'
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

        $dataIpaymu = IpaymuBayar::with([
            'detailBayar',
            'calonSiswa'
        ])->where('id_calon_siswa', $id)->first();



        $bukti = CalonSiswa::with('buktiPembayaran')
            ->where('id', $id)
            ->get();

             $itemBayar = ItemBayar::where('id_kategori', 2)
            ->get();
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
                'stsdaftar',
                'bukti',
                'itemBayar',
                'dataIpaymu'
            )
        );
    }

    public function editCalonSiswa()
    {
        $side = 'calon-siswa';
        $rows = CalonSiswa::where('id_user', Auth::id())
            ->firstOrFail();
        $itemBayar = ItemBayar::where('id_kategori', 2)
            ->get();
        $gel = Gelombang::orderBy('idx', 'asc')->get();
        $thn = TahunAjaran::orderBy('id', 'desc')->get();
        $lists = Jurusan::orderBy('nama_jurusan', 'asc')->get();
        $jobs = Pekerjaan::orderBy('nama_pekerjaan', 'asc')->get();
        $agama = Agama::orderBy('nama_agama', 'asc')->get();
        $sts_daftar = StatusDaftar::orderBy('keterangan', 'asc')->get();
        $bukti = CalonSiswa::with('buktiPembayaran')
            ->where('id_user', Auth::id())
            ->get();
        $stsdaftar = $rows->statusDaftar->keterangan ?? 'Belum Ada';
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
                'stsdaftar',
                'itemBayar',
                'bukti'
            )
        );
    }

    public function daftarSiswa()
    {

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
        ), [
            'side' => 'daftar-siswa'
        ]);
    }

    public function data()
    {
        $data = CalonSiswa::with([
            'gelombang',
            'jurusan',
            'tahunAjaran'
        ])
            ->whereHas('tahunAjaran', function ($q) {
                $q->where('isaktiv', 1);
            })
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
        $siswa = CalonSiswa::create($request->all());

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_calon_siswa',
            'aksi' => 'create',
            'user' => auth()->user()->id,
            'ip' => $request->ip(),
            'keterangan' => json_encode($request->all()),
            'serial' => url('simpan')
        ]);

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
                'email'             => 'nullable|email|max:255',
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
            $siswa->email             = $request->email;
            $siswa->save();

            /*
        |--------------------------------------------------------------------------
        | RESPONSE
        |--------------------------------------------------------------------------
        */

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_calon_siswa',
                'aksi' => !empty($id) ? 'update' : 'create',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($siswa),
                'serial' => !empty($id) ? url('ubah/' . $id) : url('simpan')
            ]);

            if ($id != null) {

                $message = 'Data registrasi & biodata siswa berhasil diperbarui';
            } else {

                $message = 'Data registrasi & biodata siswa berhasil ditambahkan';
            }

            if (auth()->user()->hasRole('calon')) {
                return redirect()
                    ->route('calon-siswa.profil')
                    ->with('success', $message);
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


    public function updateUpload(Request $request, $id)
    {
        try {

            $request->validate([

                'foto_siswa'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

                'kk'               => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

                'akta_kelahiran'   => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

                'ijazah'           => 'nullable|mimes:pdf|max:4096',

                //sudo  'raport'           => 'nullable|mimes:pdf|max:4096',

                // 'ktp_ayah'         => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

                // 'ktp_ibu'          => 'nullable|mimes:jpg,jpeg,png,pdf|max:2048',

            ]);

            $rows = CalonSiswa::where('id', $id)->first();

            if (!$rows) {

                return redirect()
                    ->back()
                    ->with('error', 'Data tidak ditemukan');
            }

            $data = [];

            /*
        |--------------------------------------------------------------------------
        | FOTO SISWA
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('foto_siswa')) {

                $file = $request->file('foto_siswa');

                $namaFile = time() . '_foto.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/foto_siswa',
                    $namaFile,
                    'public'
                );

                $data['foto_siswa'] =
                    'uploads/foto_siswa/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | KK
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('kk')) {

                $file = $request->file('kk');

                $namaFile = time() . '_kk.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/kk',
                    $namaFile,
                    'public'
                );

                $data['kk'] =
                    'uploads/kk/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | AKTA
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('akta_kelahiran')) {

                $file = $request->file('akta_kelahiran');

                $namaFile = time() . '_akta.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/akta',
                    $namaFile,
                    'public'
                );

                $data['akta_kelahiran'] =
                    'uploads/akta/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | IJAZAH
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('ijazah')) {

                $file = $request->file('ijazah');

                $namaFile = time() . '_ijazah.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/ijazah',
                    $namaFile,
                    'public'
                );

                $data['ijazah'] =
                    'uploads/ijazah/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | RAPORT
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('raport')) {

                $file = $request->file('raport');

                $namaFile = time() . '_raport.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/raport',
                    $namaFile,
                    'public'
                );

                $data['raport'] =
                    'uploads/raport/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | KTP AYAH
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('ktp_ayah')) {

                $file = $request->file('ktp_ayah');

                $namaFile = time() . '_ktp_ayah.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/ktp_ayah',
                    $namaFile,
                    'public'
                );

                $data['ktp_ayah'] =
                    'uploads/ktp_ayah/' . $namaFile;
            }

            /*
        |--------------------------------------------------------------------------
        | KTP IBU
        |--------------------------------------------------------------------------
        */

            if ($request->hasFile('ktp_ibu')) {

                $file = $request->file('ktp_ibu');

                $namaFile = time() . '_ktp_ibu.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/ktp_ibu',
                    $namaFile,
                    'public'
                );

                $data['ktp_ibu'] =
                    'uploads/ktp_ibu/' . $namaFile;
            }

            $data['updated_at'] = now();

            CalonSiswa::where('id', $id)
                ->update($data);


            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_calon_siswa',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($data),
                'serial' => url('ubah-upload/' . $id)
            ]);

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Upload dokumen berhasil'
                );
        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function pembayaran(Request $request, $id){

           try {

                DB::beginTransaction();

                $data = [];

                if ($request->hasFile('bukti_transfer')) {

                    $file = $request->file('bukti_transfer');

                    $namaFile = time() . '_bukti_transfer.' .
                        $file->getClientOriginalExtension();

                    $file->storeAs(
                        'uploads/pembayaran',
                        $namaFile,
                        'public'
                    );

                    $data['bukti_transfer'] = 'uploads/pembayaran/' . $namaFile;
                }

                $data['updated_at'] = now();

                DB::table('tb_bukti_bayar_calon')->insert([
                    'id_calon_siswa' => $id,
                    'bukti_transfer' => $data['bukti_transfer'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                LogModel::create([
                    'tanggal' => now(),
                    'tabel' => 'tb_bukti_bayar_calon',
                    'aksi' => 'insert',
                    'user' => auth()->user()->id,
                    'ip' => $request->ip(),
                    'keterangan' => json_encode($data),
                    'serial' => url('pembayaran/' . $id),
                ]);

                DB::commit();

                return redirect()
                    ->back()
                    ->with('success', 'Upload dokumen berhasil');

            } catch (\Exception $e) {

                DB::rollBack();

                // Simpan log error
                Log::error('Upload bukti transfer gagal', [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ]);

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Upload dokumen gagal. ' . $e->getMessage());
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

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_calon_siswa',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($siswa),
                'serial' => url('ubah-orangtua/' . $id)
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

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_calon_siswa',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($siswa),
                'serial' => url('ubah-registrasi/' . $id)
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

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_calon_siswa',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($siswa),
                'serial' => url('ubah-status/' . $id)
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
        $calonSiswa = CalonSiswa::findOrFail($id);
        $calonSiswa->delete();

        LogModel::create([
            'tanggal' => now(),
            'tabel' => 'tb_calon_siswa',
            'aksi' => 'delete',
            'user' => auth()->user()->id,
            'ip' => request()->ip(),
            'keterangan' => json_encode($calonSiswa),
            'serial' => url('hapus/' . $id)
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    public function callback(Request $request)
    {
        Log::info('Callback iPaymu', $request->all());

        return response()->json([
            'success' => true
        ]);
    }

    public function ipaymu(Request $request)
    {
        // Ambil data calon siswa


        $siswa = CalonSiswa::where('id_user', auth()->user()->id)->first();

        $apiKey = 'SANDBOX18AEF286-79CD-44C6-8764-2C89B94C7872';
        $va      = '0000007864357063';

        try {

            $referenceId = 'INV-' . time();
            $userId      = auth()->id();

            $nominal = (int) preg_replace('/[^0-9]/', '', $request->nominal);

            DB::beginTransaction();

            /** =====================
             * SIMPAN DATA BAYAR
             * ===================== */
            $orderId = DB::table('tb_ipaymu_bayar')->insertGetId([
                'id_calon_siswa'   => $siswa->no_daftar,
                'id_tahun'    => date('Y'),
                'id_bulan'    => date('m'),
                'tgl_bayar'   => $request->tgl_trans ?? now(),
                'id_kasir'    => $userId,
                'via'         => 4,
                'sts_bayar'   => 2, // pending
                'no_kwitansi' => $request->no_kwitansi,
                'keterangan'  => $request->comments,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            $itemBayar = ItemBayar::find($request->item);

            DB::table('tb_ipaymu_det_bayar')->insert([
                'id_bayar' => $orderId,
                'nama_item' => $itemBayar->nama_item,
                'id_item' =>   $itemBayar->id,
                'jml_bayar' => $nominal,
                'id_user'  => $userId,
            ]);

            /** =====================
             * AMBIL DATA MAHASISWA
             * ===================== */


            /** =====================
             * DATA IPAYMU
             * ===================== */
            $data = [
                'buyerName'  => $siswa->nama_lengkap,
                'buyerPhone' => $siswa->no_hp,
                'buyerEmail' => $siswa->email,
                'amount'    => (int) $nominal,
                'referenceId' => $referenceId,
                'product'   => [$itemBayar->nama_item],
                'qty'       => [1],
                'price'     => [(int) $nominal],
                'notifyUrl' => url('api/calon-siswa/notifyPembayaran'),
                'returnUrl' => route('calon-siswa.success-pembayaran'),
                'cancelUrl' => route('calon-siswa.cancel-pembayaran'),
            ];

            $timestamp   = now()->setTimezone('Asia/Jakarta')->format('YmdHis');
            $body        = json_encode($data, JSON_UNESCAPED_SLASHES);
            $bodyHash    = strtolower(hash('sha256', $body));
            $stringToSign = "POST:{$va}:{$bodyHash}:{$apiKey}";
            $signature   = hash_hmac('sha256', $stringToSign, $apiKey);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'signature'    => $signature,
                'va'           => $va,
                'timestamp'    => $timestamp,
            ])->post('https://sandbox.ipaymu.com/api/v2/payment', $data);

            /** =====================
             * CEK RESPONSE IPAYMU
             * ===================== */
            if (!$response->successful() || !isset($response['Data']['Url'])) {
                DB::rollBack();
                return back()->with('error', 'Gagal membuat transaksi iPaymu');
            }

            DB::table('tb_ipaymu_transaction')->insert([
                'id_bayar'        => $orderId,
                'ipaymu_ref'      => $referenceId,
                'amount'          => (int) $nominal,
                'payment_url'     => $response['Data']['Url'],
                'session_id'      => $response['Data']['SessionID'] ?? null,
                'ipaymu_response' => json_encode($response['Data']),
                'status'          => 'pending',
                'signature'       => $signature,
                'created_at'      => now(),
            ]);



            DB::commit();

            return redirect($response['Data']['Url']);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    function successPembayaran()
    {

        return redirect()->route('calon-siswa.profil')->with('success', 'pembayaran Berhasil');
    }

    function cancelPembayaran()
    {

        return redirect()->route('calon-siswa.profil');
    }

    public function notifyPembayaran(Request $request)
    {
        // =========================
        // AMBIL DATA WAJIB
        // =========================
        $referenceId = $request->input('reference_id');


        if (!$referenceId) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        DB::beginTransaction();

        // =========================
        // CARI TRANSAKSI (LOCK)
        // =========================
        $trx = DB::table('tb_ipaymu_transaction')
            ->where('ipaymu_ref', $referenceId)
            ->lockForUpdate()
            ->first();

        if (!$trx) {
            DB::rollBack();
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        // =========================
        // IDEMPOTENCY CHECK
        // =========================
        if ($trx->status === 'success') {
            DB::commit();
            return response()->json(['message' => 'Already processed'], 200);
        }

        // =========================
        // VALIDASI SETTLED
        // =========================
        // if ($statusCode !== 1 || $settlement !== 'settled') {
        //     DB::commit();
        //     return response()->json(['message' => 'Payment not settled'], 200);
        // }

        // =========================
        // UPDATE TRANSAKSI
        // =========================
        DB::table('tb_ipaymu_transaction')
            ->where('id', $trx->id)
            ->update([
                'status'          => 'success',
                'payment_method' => $request->input('via'),      // contoh: va
                'payment_channel' => $request->input('channel'),  // contoh: danamon
                'paid_at'        => $request->input('paid_at') ?? now(),
                'ipaymu_response' => json_encode($request->all()),
                'updated_at'     => now(),
            ]);

        $ipaymuBayar = DB::table('tb_ipaymu_bayar')
            ->where('id', $trx->id_bayar)
            ->first();

        $ipaymuDetBayar = DB::table('tb_ipaymu_det_bayar')
            ->where('id_bayar', $trx->id_bayar)
            ->first();

        $bayar = BayarCalonSiswa::create([
            'id_tahun'    => date('Y'),
            'id_bulan'    => date('m'),
            'id_calon_siswa'    => $ipaymuBayar->id_calon_siswa,
            'no_kwitansi' => $request->no_kwitansi,
            'tgl_bayar'   => now(),
            'id_kasir'    => auth()->user()->id,
            'keterangan'  => $request->keterangan ?? 'Pembayaran iPaymu',
        ]);

        $idBayar = $bayar->id;

        DetBayarCalonSiswa::create([
            'id_bayar'   => $idBayar,
            'id_item'    => $ipaymuDetBayar->id_item,
            'jml_bayar'  => $ipaymuDetBayar->jml_bayar,
            'id_cicilan' => $request->cicilan,
        ]);


        BayarCalonSiswa::updateBayar($idBayar);
        BayarCalonSiswa::updateKewajiban($idBayar);

        DB::commit();

        return response()->json(['message' => 'Notifikasi diterima'], 200);
    }

    function dataIpaymu($id){

        $data = IpaymuBayar::with([
            'detailBayar',
            'calonSiswa'
        ])->find($id);

        return response()->json($data);
    }
}
