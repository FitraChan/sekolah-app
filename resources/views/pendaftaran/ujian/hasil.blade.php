@extends('layout.main')

@section('tittle')
Target Pendaftaran
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Target Pendaftaran</li>
</ol>
@endsection

@section('body')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Hasil Ujian
    </h2>
</div>

<div class="intro-y box p-8 mt-5 text-center">
    <div class="text-slate-500">
        {{ $peserta->ujian->nama_ujian }}
    </div>

    @if ($peserta->ujian->tampil_hasil)
        <div class="text-6xl font-bold mt-5
            {{ $peserta->hasil === 'lulus'
                ? 'text-success'
                : 'text-danger' }}">
            {{ number_format($peserta->nilai, 2) }}
        </div>

        <div class="mt-4">
            @if ($peserta->hasil === 'lulus')
                <span class="px-4 py-2 rounded-full bg-success text-white">
                    LULUS
                </span>
            @else
                <span class="px-4 py-2 rounded-full bg-danger text-white">
                    TIDAK LULUS
                </span>
            @endif
        </div>

        <div class="grid grid-cols-12 gap-4 mt-8">
            <div class="col-span-12 md:col-span-4">
                <div class="border rounded p-4">
                    <div class="text-2xl font-medium text-success">
                        {{ $peserta->jumlah_benar }}
                    </div>
                    <div class="text-slate-500">
                        Jawaban Benar
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-4">
                <div class="border rounded p-4">
                    <div class="text-2xl font-medium text-danger">
                        {{ $peserta->jumlah_salah }}
                    </div>
                    <div class="text-slate-500">
                        Jawaban Salah
                    </div>
                </div>
            </div>

            <div class="col-span-12 md:col-span-4">
                <div class="border rounded p-4">
                    <div class="text-2xl font-medium text-warning">
                        {{ $peserta->tidak_dijawab }}
                    </div>
                    <div class="text-slate-500">
                        Tidak Dijawab
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-8 text-lg">
            Ujian telah selesai.
        </div>

        <div class="text-slate-500 mt-2">
            Hasil ujian akan diumumkan oleh pihak sekolah.
        </div>
    @endif

    <a
        href="{{ route('calon-siswa.ujian.index') }}"
        class="btn btn-primary mt-8"
    >
        Kembali ke Daftar Ujian
    </a>
</div>

@endsection