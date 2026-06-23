<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jurusan;
use App\Models\User;
use App\Models\Gtk;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;



class AuthController extends Controller
{
    public function FormLogin()
    {
        return view('auth.login');
    }

    public function Login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect('/admin');
        }

        if ($user->hasRole('calon')) {
            return redirect()->route('calon-siswa.profil');
        }

        if ($user->hasRole('Akademik')) {
            return redirect()->route('admin');
        }

        if ($user->hasRole('guru')) {
            return redirect()->route('dashboard');
        }

        Auth::logout();

        return back()->with('error', 'Role tidak diizinkan');
    }

    return back()->with('error', 'Email atau password salah');
}

    public function Logout()
    {
        Auth::logout();
        Session::flush();

        return redirect()->route('form-login');
    }

    public function registerSiswa()
    {
        $data['jurusan'] = Jurusan::orderBy('nama_jurusan')
            ->get();

        return view('auth.registrasi', $data);
    }

    public function registerGuru()
    {        
        return view('auth.registrasiGuru');
    }



    public function cekregister(Request $request)
    {
       $request->validate([
            'nama_lengkap'      => 'required',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6',
            'no_telp'           => 'required',
            'id_jurusan'        => 'required',
            'nama_sekolah_asal' => 'required',
        ],[
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        DB::beginTransaction();

        try {

            $email      = strip_tags($request->email);
            $password   = $request->password;
            $nama       = htmlspecialchars($request->nama_lengkap);
            $telp       = $request->no_telp;
            $jurusan    = $request->id_jurusan;
            $asal       = htmlspecialchars($request->nama_sekolah_asal);

            $gelombang = DB::table('tb_gelombang')->where('is_current', 1)->first();

            $thn_ajaran = DB::table('tb_thn_ajaran')->where('isaktiv', 1)->first();


            $thn = $thn_ajaran->id;

            $cek = DB::table('tb_tmp_siswa')
                ->where('email', $email)
                ->exists();

            if ($cek) {
                return back()
                    ->withInput()
                    ->with('error', 'Email sudah terdaftar.');
            }

            $tb = DB::table('tb_template_bayar')
                ->select('id')
                ->where([
                    'id_tahun'      => $thn,
                    'id_gelombang'  => $gelombang->id,
                    'jns_kelas'     => 1,
                    'sts'           => 1
                ])
                ->first();

            $leMineral = DB::table('tb_tmp_siswa')
                ->where('id_thn_ajaran', $thn)
                ->where('tmp_daftar', 'online')
                ->max('no_daftar');

            $hasil = 1;

            if (!empty($leMineral)) {

                $bilangan = substr($leMineral, 1, 3);

                $hasil = ((int)$bilangan) + 1;
            }

            $nodaftar = sprintf("E%03d", $hasil);

            $userId = DB::table('users')->insertGetId([
                'name'              => $nama,
                'email'             => $email,
                'password'          => Hash::make($password),
                'email_verified_at' => null,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            $user = User::find($userId);
            $user->assignRole('calon');

            // 2. Insert calon siswa
            $id = DB::table('tb_tmp_siswa')->insertGetId([
                'id_user'            => $userId,
                'no_daftar'          => $nodaftar,
                'no_urut'            => $hasil,
                'nama_lengkap'       => $nama,
                'username'           => $email,
                'password'           => Hash::make($password),
                'password2'          => $password,
                'no_hp'              => $telp,
                'id_jurusan'         => $jurusan,
                'email'              => $email,
                'nama_sekolah_asal'  => $asal,
                'id_gelombang'       => $gelombang->id,
                'tmp_daftar'         => 'online',
                'tgl_daftar'         => now()->format('Y-m-d'),
                'id_thn_ajaran'      => $thn_ajaran->id,
                'id_petugas'         => 6,
                'status_daftar'      => '-1',
                'id_template_bayar'  => $tb->id ?? 0,
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            DB::table('tb_notifikasi')->insert([
                'judul'      => 'Daftar Online',
                'isi'        => 'Pendaftar siswa baru online a.n '
                    . $nama .
                    '<br> No. Telp ' . $telp,
                'tujuan'     => 'PSB',
                'dari'       => 'Laravel',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Session::put('ppdb_in', [
                'id'            => $id,       // id tb_tmp_siswa
                'id_user'       => $userId,   // id users
                'email'         => $email,
                'no_daftar'     => $nodaftar,
                'nama_lengkap'  => $nama,
                'foto'          => 'avatar.png',
                'telp'          => $telp,
                'tahun'         => $thn_ajaran->id,
                'gelombang'     => $gelombang->id,
                'idlevel'       => 7,
            ]);

            // WhatsApp Gateway
            $pesan = "Selamat {$nama}, anda telah terdaftar di SMK Pandawa Bali Global Abiansemal. Silahkan melakukan pembayaran pendaftaran dan upload bukti bayar melalui halaman personal anda.";

            $pesanAdmin = "Pendaftaran online baru
            Nama : {$nama}
            No Daftar : {$nodaftar}
            No HP : {$telp}";

            // Contoh pemanggilan service WA
            // app(WhatsappService::class)->send($telp, $pesan);
            // app(WhatsappService::class)->send('081338686666', $pesanAdmin);

            DB::commit();

         return redirect('/')
                ->with('success', 'Pendaftaran berhasil.');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function cekRegisterGuru(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'nama_gtk' => 'required|string|max:100',
            'nik' => 'nullable|string|max:50',
            'jk' => 'nullable|in:L,P',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|unique:users,email',     
            //'email' => 'required',      
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->nama_gtk,
            'email' => $request->email, // login pakai akun_ptk
            'password' => Hash::make($request->password),
            'role' => 'guru',
            
        ]);

        $user->assignRole('guru');

        // 2. Simpan ke database
        $guru = Gtk::create([
            'nama_gtk' => $request->nama_gtk,
            'nik' => $request->nik,
            'jk' => $request->jk,
            'no_hp' => $request->no_hp,
            'email' => $request->email,                       
            'user_id' => $user->id
           
        ]);

        // 3. Response
         return redirect('/');
    }
}
