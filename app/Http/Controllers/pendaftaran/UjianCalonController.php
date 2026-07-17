<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\UjianCalon;
use App\Models\UjianJawabanCalon;
use App\Models\UjianPesertaCalon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UjianCalonController extends Controller
{
    private function calonSiswaId(): int
    {
        /*
         * Sesuaikan dengan sistem login Anda.
         *
         * Contoh jika ID calon siswa berada langsung pada user:
         * return auth()->user()->calon_siswa_id;
         *
         * Contoh jika user memiliki relasi calonSiswa:
         * return auth()->user()->calonSiswa->id;auth()->user()->id
         */

        return auth()->user()->id;
    }

    public function index(): View
    {
        $calonSiswaId = $this->calonSiswaId();

        $data = UjianCalon::query()
            ->where('status', true)
            ->withCount([
                'soal' => fn ($query) => $query->where('status', true),
            ])
            ->with([
                'peserta' => fn ($query) =>
                    $query->where('calon_siswa_id', $calonSiswaId),
            ])
            ->latest()
            ->get();

        return view('pendaftaran.ujian.index', compact('data'));
    }

    public function mulai(UjianCalon $ujian): RedirectResponse
    {
        abort_unless($ujian->status, 404);

        $sekarang = now();

        if ($ujian->tanggal_mulai &&
            $sekarang->lt($ujian->tanggal_mulai)) {
            return back()->with(
                'error',
                'UjianCalon belum dapat dimulai.'
            );
        }

        if ($ujian->tanggal_selesai &&
            $sekarang->gt($ujian->tanggal_selesai)) {
            return back()->with(
                'error',
                'Waktu pelaksanaan ujian telah berakhir.'
            );
        }

        if (!$ujian->soal()->where('status', true)->exists()) {
            return back()->with(
                'error',
                'Soal ujian belum tersedia.'
            );
        }

        $peserta = UjianPesertaCalon::firstOrCreate(
            [
                'id_ujian' => $ujian->id,
                'calon_siswa_id' => $this->calonSiswaId(),
            ],
            [
                'status' => 'belum',
                'hasil' => 'belum_dinilai',
            ]
        );

        if ($peserta->status === 'selesai') {
            return redirect()->route(
                'calon-siswa.ujian.hasil',
                $peserta
            );
        }

        if ($peserta->status === 'belum') {
            $peserta->update([
                'waktu_mulai' => now(),
                'status' => 'sedang',
            ]);
        }

        return redirect()->route(
            'calon-siswa.ujian.kerjakan',
            $peserta
        );
    }

    public function kerjakan(UjianPesertaCalon $peserta): View|RedirectResponse
    {
        abort_if(
            $peserta->calon_siswa_id !== $this->calonSiswaId(),
            403
        );

        if ($peserta->status === 'selesai') {
            return redirect()->route(
                'calon-siswa.ujian.hasil',
                $peserta
            );
        }

        $peserta->load('ujian');

        $batasWaktu = $peserta->waktu_mulai
            ->copy()
            ->addMinutes($peserta->ujian->durasi);

        if (now()->greaterThanOrEqualTo($batasWaktu)) {
            return $this->prosesPenilaian($peserta, []);
        }

        $query = $peserta->ujian
            ->soal()
            ->where('status', true);

        $soal = $peserta->ujian->acak_soal
            ? $query->inRandomOrder()->get()
            : $query->orderBy('id')->get();

        return view(
            'calon-siswa.ujian.kerjakan',
            compact('peserta', 'soal', 'batasWaktu')
        );
    }

    public function submit(
        Request $request,
        UjianPesertaCalon $peserta
    ): RedirectResponse {
        abort_if(
            $peserta->calon_siswa_id !== $this->calonSiswaId(),
            403
        );

        if ($peserta->status === 'selesai') {
            return redirect()->route(
                'calon-siswa.ujian.hasil',
                $peserta
            );
        }

        $jawaban = $request->input('jawaban', []);

        return $this->prosesPenilaian($peserta, $jawaban);
    }

    private function prosesPenilaian(
        UjianPesertaCalon $peserta,
        array $jawaban
    ): RedirectResponse {
        $peserta->load('ujian');

        $soal = $peserta->ujian
            ->soal()
            ->where('status', true)
            ->get();

        DB::transaction(function () use ($peserta, $soal, $jawaban) {
            $jumlahBenar = 0;
            $jumlahSalah = 0;
            $tidakDijawab = 0;
            $nilaiDiperoleh = 0;
            $totalBobot = (float) $soal->sum('bobot');

            foreach ($soal as $item) {
                $jawabanPeserta = isset($jawaban[$item->id])
                    ? strtoupper($jawaban[$item->id])
                    : null;

                $benar = $jawabanPeserta === $item->jawaban_benar;

                if ($jawabanPeserta === null) {
                    $tidakDijawab++;
                } elseif ($benar) {
                    $jumlahBenar++;
                    $nilaiDiperoleh += (float) $item->bobot;
                } else {
                    $jumlahSalah++;
                }

                UjianJawabanCalon::updateOrCreate(
                    [
                        'peserta_id' => $peserta->id,
                        'soal_id' => $item->id,
                    ],
                    [
                        'jawaban' => $jawabanPeserta,
                        'benar' => $benar,
                        'nilai' => $benar ? $item->bobot : 0,
                    ]
                );
            }

            $nilaiAkhir = $totalBobot > 0
                ? round(($nilaiDiperoleh / $totalBobot) * 100, 2)
                : 0;

            $hasil = $nilaiAkhir >= $peserta->ujian->nilai_minimal
                ? 'lulus'
                : 'tidak_lulus';

            $peserta->update([
                'waktu_selesai' => now(),
                'nilai' => $nilaiAkhir,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'tidak_dijawab' => $tidakDijawab,
                'status' => 'selesai',
                'hasil' => $hasil,
            ]);
        });

        return redirect()->route(
            'calon-siswa.ujian.hasil',
            $peserta
        );
    }

    public function hasil(UjianPesertaCalon $peserta): View
    {
        abort_if(
            $peserta->calon_siswa_id !== $this->calonSiswaId(),
            403
        );

        abort_unless($peserta->status === 'selesai', 404);

        $peserta->load('ujian');

        return view(
            'calon-siswa.ujian.hasil',
            compact('peserta')
        );
    }
}