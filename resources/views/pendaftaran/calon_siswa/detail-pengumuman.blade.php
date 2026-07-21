@extends('layout.main')

@section('tittle')
Detail Pengumuman
@endsection

@section('body')

<div class="max-w-4xl mx-auto p-6">

    <div class="mb-5">

        <a href="{{ route('pengumumanCalon.index') }}"
            class="btn btn-outline-secondary">

            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>

            Kembali

        </a>

    </div>

    <div class="box p-6">

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

            <div>

                <h1 class="text-2xl font-bold text-slate-800">
                    {{ $pengumuman->judul }}
                </h1>

                <div class="text-slate-500 text-sm mt-3">

                    <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1"></i>

                    {{ $pengumuman->tanggal_kirim
                        ? \Carbon\Carbon::parse($pengumuman->tanggal_kirim)->format('d M Y H:i')
                        : '-' }}

                </div>

            </div>

        </div>

        <hr class="my-6">

        <div class="prose max-w-none text-slate-700 leading-relaxed">

            {!! $pengumuman->pesan !!}

        </div>

    </div>

</div>

@endsection