<?php

namespace App\Http\Controllers\guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\DetailQuiz;
use Illuminate\Http\Request;
use App\Models\MasterJadwal;
use App\Models\Gtk;
use App\Models\TransAjar;
use App\Models\Quiz;
use App\Models\LogModel;
use App\Models\DetQuiz;
use App\Models\JenisSoal;
use App\Models\Soal;




use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;





class PenilaianGuruController extends Controller
{
    public function index()
    {
        $konfig = konfig();
        $smt = ($konfig['smt'] ?? 1) == 1 ? 'Ganjil' : 'Genap';
        return view('guru.pbm.index', [
            'side'  => 'pbm',
            'smt'   => $smt,
            'thn' => $konfig['id_tahun']

        ]);
    }

    public function data(Request $request)
    {

        $gtk = Gtk::where('user_id', auth()->user()->id)->first();

        $query = MasterJadwal::with([
            'tahun',
            'kelas',
            'mapel',
            'guru'
        ]);


        $query->where('id_tahun', konfig()['id_tahun']);

        $query->where('semester', konfig()['smt']);

        $query->where('id_gtk', $gtk->id);


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
                    'kelas'         => $item->kelas->kelas ?? '',
                    'nama_mapel'    => $item->mapel->nama_mapel ?? '',
                    'nama_gtk'      => $item->guru->nama_gtk ?? '',
                ];
            });

        return response()->json($data);
    }




    public function dataMateri(Request $request, $id = null)
    {
        $data['isi'] = TransAjar::withCount([
            'hadir as H',
            'izin as I',
            'sakit as S',
            'alfa as A',
        ])
            ->where('idjadwal', $id)
            ->orderBy('idpertemuan')
            ->orderBy('tgl')
            ->get()
            ->map(function ($item) {

                return [
                    'id'                  => $item->id,
                    'idjadwal'            => $item->idjadwal,
                    'idpertemuan'         => $item->idpertemuan,
                    'tgl'                 => $item->tgl
                        ? date('d-m-Y', strtotime($item->tgl))
                        : '',
                    'judul_materi'        => $item->judul_materi ?? '',
                    'nama_guru_pengganti' => $item->guruPengganti->nama_gtk ?? '',
                    'judul_tugas'         => $item->judul_tugas ?? '',
                    'keterangan'          => $item->keterangan ?? '',

                    'H' => $item->H,
                    'I' => $item->I,
                    'S' => $item->S,
                    'A' => $item->A,
                ];
            });

        $data['master'] = MasterJadwal::with(['kelas', 'mapel', 'guru'])
            ->where('id', $id)
            ->first();

        $data['guru'] = Gtk::select('id', 'nama_gtk')->get();

        $data['ujian'] = Quiz::where('master_kelas_id', $id)->get();
        $data['id'] = $id;

        return view('guru.materi_pbm.index', $data);

        //return response()->json($data);
    }

    public function dataAbsen(Request $request, $id = null)
    {
        $data['guru'] = Gtk::select('id', 'nama_gtk')->get();

        $data['master'] = TransAjar::with([
            'jadwal.kelas',
            'jadwal.mapel',
            'jadwal.guru',
            'guruPengganti'
        ])->findOrFail($id);

        $data['id'] = $id;


        $data['isi'] = Absensi::with([
            'siswa'
        ])
            ->where('idtransajar', $id)
            ->orderBy('nipd')
            ->get()
            ->map(function ($item) {

                return [
                    'id'         => $item->id,
                    'nipd'       => $item->nipd,
                    'nama_siswa' => $item->siswa->nama_lengkap ?? '',
                    'sts_hadir'  => $item->sts_hadir,
                    'keterangan' => $item->keterangan,
                ];
            });

        return view('guru.materi_pbm.materi.absen', $data);
    }

    public function editMateri($id)
    {
        $data['materi'] = TransAjar::findOrFail($id);

        $data['master'] = MasterJadwal::with(['kelas', 'mapel'])
            ->findOrFail($data['materi']->idjadwal);

        $data['guru'] = Gtk::select('id', 'nama_gtk')->get();

        $data['id'] = $data['materi']->idjadwal;

        return view('guru.materi_pbm.materi.tambah', $data);
    }

    public function tambahMateri($id)
    {
        $guru = Gtk::select('id', 'nama_gtk')->get();

        $master = MasterJadwal::with(['kelas', 'mapel', 'guru'])
            ->where('id', $id)
            ->first();



        return view('guru.materi_pbm.materi.tambah', [
            'side'  => 'pbm',
            'guru' => $guru,
            'master' => $master,
            'id' => $id,
            'materi' => null
        ]);
    }


    public function simpanMateri(Request $request)
    {
        DB::beginTransaction();

        try {

            $idjadwal = $request->idjadwal;
            $idpertemuan = $request->idpertemuan;

            $data = [
                'idjadwal'       => $request->idjadwal,
                'idpertemuan'    => $request->idpertemuan,
                'judul_materi'   => $request->judul_materi,
                'materi'         => $request->materi,
                'url_video'      => $request->url_video,
                'is_youtube'     => $request->is_youtube,
                'judul_tugas'    => $request->judul_tugas,
                'tugas'          => $request->tugas,
                'keterangan'     => $request->keterangan,
                'guru_pengganti' => $request->guru_pengganti,
                'tgl'            => date('Y-m-d', strtotime($request->tgl)),
                'tgl_batas_submit' => !empty($request->tgl_batas_submit)
                    ? date('Y-m-d H:i:s', strtotime($request->tgl_batas_submit))
                    : null,
                'jml_h'          => $request->jml_h,
                'jml_i'          => $request->jml_i,
                'jml_s'          => $request->jml_s,
                'jml_a'          => $request->jml_a,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];

            // Materi 1
            if ($request->hasFile('url_materi_1')) {

                $file = $request->file('url_materi_1');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_1_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_1'] =  'uploads/materi/' . $fileName;
            }

            // Materi 2
            if ($request->hasFile('url_materi_2')) {

                $file = $request->file('url_materi_2');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_2_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                // $file->move($uploadPath, $fileName);

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_2'] =  'uploads/materi/' . $fileName;
            }

            // Materi 3
            if ($request->hasFile('url_materi_3')) {

                $file = $request->file('url_materi_3');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_3_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_3'] =  'uploads/materi/' . $fileName;
            }

            // Tugas
            if ($request->hasFile('url_tugas')) {

                $file = $request->file('url_tugas');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_tugas_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();


                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_tugas'] =  'uploads/materi/' . $fileName;
            }

            // Insert tb_trans_ajar
            $idt = DB::table('tb_trans_ajar')->insertGetId($data);

            // Insert tb_hadir_siswa
            DB::statement("
                INSERT INTO tb_hadir_siswa(idtransajar, nipd)
                SELECT ?, tb_nilai.nipd
                FROM tb_nilai
                WHERE tb_nilai.idjadwal = ?
                ORDER BY tb_nilai.nipd
            ", [$idt, $idjadwal]);


            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_trans_ajar',
                'aksi' => 'create',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($data),
                'serial' => url('pbm/simpanMateri')
            ]);


            DB::commit();

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Data berhasil disimpan'
                );

            //  return response()->json([
            //     'success' => true,
            //     'data' => $data,
            //     'msg' => 'Data berhasil dihapus'
            // ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    public function hapusMateri($id)
    {
        try {

            DB::table('tb_trans_ajar')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'msg' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function updateMateri(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $materi = DB::table('tb_trans_ajar')
                ->where('id', $id)
                ->first();

            if (!$materi) {
                return redirect()
                    ->back()
                    ->with('error', 'Data tidak ditemukan');
            }

            $idjadwal = $request->idjadwal;
            $idpertemuan = $request->idpertemuan;

            $data = [
                'idjadwal'       => $request->idjadwal,
                'idpertemuan'    => $request->idpertemuan,
                'judul_materi'   => $request->judul_materi,
                'materi'         => $request->materi,
                'url_video'      => $request->url_video,
                'is_youtube'     => $request->is_youtube,
                'judul_tugas'    => $request->judul_tugas,
                'tugas'          => $request->tugas,
                'keterangan'     => $request->keterangan,
                'guru_pengganti' => $request->guru_pengganti,
                'tgl'            => date('Y-m-d', strtotime($request->tgl)),
                'tgl_batas_submit' => !empty($request->tgl_batas_submit)
                    ? date('Y-m-d H:i:s', strtotime($request->tgl_batas_submit))
                    : null,
                'jml_h'          => $request->jml_h,
                'jml_i'          => $request->jml_i,
                'jml_s'          => $request->jml_s,
                'jml_a'          => $request->jml_a,
                'updated_at'     => now(),
            ];

            // =====================
            // Materi 1
            // =====================
            if ($request->hasFile('url_materi_1')) {

                $file = $request->file('url_materi_1');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_1_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_1'] = 'uploads/materi/' . $fileName;
            }

            // =====================
            // Materi 2
            // =====================
            if ($request->hasFile('url_materi_2')) {

                $file = $request->file('url_materi_2');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_2_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_2'] = 'uploads/materi/' . $fileName;
            }

            // =====================
            // Materi 3
            // =====================
            if ($request->hasFile('url_materi_3')) {

                $file = $request->file('url_materi_3');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_materi_3_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_materi_3'] = 'uploads/materi/' . $fileName;
            }

            // =====================
            // Tugas
            // =====================
            if ($request->hasFile('url_tugas')) {

                $file = $request->file('url_tugas');

                $fileName = $idjadwal . '_' . $idpertemuan .
                    '_tugas_' . date('dmY') . '.' .
                    $file->getClientOriginalExtension();

                $file->storeAs(
                    'uploads/materi',
                    $fileName,
                    'public'
                );

                $data['url_tugas'] = 'uploads/materi/' . $fileName;
            }

            DB::table('tb_trans_ajar')
                ->where('id', $id)
                ->update($data);


            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_trans_ajar',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($data),
                'serial' => url('pbm/updateMateri')
            ]);

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function simpanUjian(Request $request)
    {
        // $request->validate([
        //     'judul'            => 'required|string|max:255',
        //     'master_kelas_id'  => 'required',
        //     'tgl_quiz'         => 'required|date',
        //     'tgl_mulai'        => 'required|date',
        //     'tgl_selesai'      => 'required|date',
        //     'durasi'           => 'required|integer|min:1',
        // ]);

        try {

            $quiz =   Quiz::create([
                'created_by'      => auth()->user()->id,
                'judul'           => $request->judul,
                'master_kelas_id' => $request->master_kelas_id,
                'tgl_quiz'        => date('Y-m-d', strtotime($request->tgl_quiz)),
                'tgl_mulai'       => date('Y-m-d H:i:s', strtotime($request->tgl_mulai)),
                'tgl_selesai'     => date('Y-m-d H:i:s', strtotime($request->tgl_selesai)),
                'durasi'          => $request->durasi,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ]);

            LogModel::create([
                'tanggal' => now(),
                'tabel' => 'tb_trans_ajar',
                'aksi' => 'update',
                'user' => auth()->user()->id,
                'ip' => $request->ip(),
                'keterangan' => json_encode($quiz),
                'serial' => url('pbm/updateMateri')
            ]);

            return redirect()
                ->back()
                ->with('success', 'Data berhasil ditambah');
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'title'   => 'Peringatan',
                'msg'     => 'Gagal menyimpan data',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function deleteUjian(Request $request, $id)
    {
        try {

            $quiz = Quiz::findOrFail($id);

            // Simpan data sebelum dihapus
            $data = $quiz->toArray();

            LogModel::create([
                'tanggal'    => now(),
                'tabel'      => 'quizs',
                'aksi'       => 'delete',
                'user'       => auth()->id(),
                'ip'         => $request->ip(),
                'keterangan' => json_encode($data),
                'serial'     => url('ujian/' . $id),
            ]);

            $quiz->delete();

            return response()->json([
                'success' => true,
                'msg'     => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'msg'     => 'Data gagal dihapus.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function dataDetQuiz(Request $request, $id = null)
    {
        $data['jenis_soal'] = JenisSoal::get();

$konfig = konfig();

        $data['id'] = $id;


        $data['soals'] = DetailQuiz::with([
            'quiz',
            'soal'
        ])
            ->where('quiz_id', $id)
            ->orderBy('soal_id')
            ->get()
            ->map(function ($item) {

                return [
                    'id'              => $item->id,
                    'soal_id'         => $item->soal_id,

                    'soal'            => $item->soal->soal,
                    'jenis_soal_id'   => $item->soal->jenis_soal_id,
                    'jawaban_a'       => $item->soal->jawaban_a,
                    'jawaban_b'       => $item->soal->jawaban_b,
                    'jawaban_c'       => $item->soal->jawaban_c,
                    'jawaban_d'       => $item->soal->jawaban_d,
                    'jawaban_e'       => $item->soal->jawaban_e,
                    'jawaban_benar'   => $item->soal->jawaban_benar,
                ];
            });

             // Data ujian
    $data['ujian'] = Quiz::findOrFail($id);

    // Data master jadwal
    $data['idmapel'] = MasterJadwal::find($data['ujian']->master_kelas_id);

    // Jadwal guru
    $data['mapel'] = MasterJadwal::select(
            'id',
            'id_mapel',
            'nama_mapel',
            'id_gtk',
            'nkelas',
            'nama_kelas'
        )
        ->where('id_gtk', auth()->id())
        ->where('id_tahun', $konfig['id_tahun'])
        ->where('semester', $konfig['smt'])
        ->get();

    // Master soal
    $data['mastersoal'] = Soal::where('lecture_id', $user->id)->get();

    /*
    Jika ingin sesuai komentar CodeIgniter:

    $data['mastersoal'] = MasterSoal::where('lecture_id',$user->id)
        ->where('mapel_id',$data['idmapel']->id_mapel)
        ->get();
    */

    // Jawaban siswa
    $data['jawabansiswa'] = DB::table('tb_nilai')
        ->leftJoin('tb_siswa', 'tb_nilai.nipd', '=', 'tb_siswa.nipd')
        ->leftJoin('quizs', 'tb_nilai.idjadwal', '=', 'quizs.master_kelas_id')
        ->leftJoin('jawaban_pesertas', function ($join) {
            $join->on('tb_nilai.nipd', '=', 'jawaban_pesertas.peserta_id')
                 ->on('quizs.id', '=', 'jawaban_pesertas.quiz_id');
        })
        ->select(
            'tb_nilai.nipd',
            'jawaban_pesertas.id',
            'tb_siswa.nama_lengkap',
            'jawaban_pesertas.quiz_id',
            'jawaban_pesertas.tgl_mulai_quiz',
            'jawaban_pesertas.tgl_selesai_quiz',
            'jawaban_pesertas.total_skor'
        )
        ->where('tb_nilai.idjadwal', $data['ujian']->master_kelas_id)
        ->where('quizs.id', $id)
        ->get();

        return view('guru.materi_pbm.materi.absen', $data);
    }
}
