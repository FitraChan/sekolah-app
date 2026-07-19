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
        Ujian Calon Siswa
    </h2>
</div>

@if (session('error'))
    <div class="alert alert-danger mt-5">
        {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-12 gap-6 mt-5">
    @forelse ($data as $item)
        @php
            $peserta = $item->peserta->first();
        @endphp

        <div class="col-span-12 md:col-span-6 xl:col-span-4 intro-y">
            <div class="box p-5">
                <div class="flex items-start">
                    <i
                        data-lucide="clipboard-list"
                        class="w-10 h-10 text-primary"
                    ></i>

                    <div class="ml-auto">
                        @if ($peserta?->status === 'selesai')
                            <span class="px-2 py-1 rounded bg-success text-white">
                                Selesai
                            </span>
                        @elseif ($peserta?->status === 'sedang')
                            <span class="px-2 py-1 rounded bg-warning text-white">
                                Sedang dikerjakan
                            </span>
                        @else
                            <span class="px-2 py-1 rounded bg-primary text-white">
                                Tersedia
                            </span>
                        @endif
                    </div>
                </div>

                <div class="text-xl font-medium mt-5">
                    {{ $item->nama_ujian }}
                </div>

                <div class="text-slate-500 mt-2">
                    {{ $item->deskripsi }}
                </div>

                <div class="border-t mt-5 pt-4">
                    <div class="flex justify-between mb-2">
                        <span>Jumlah soal</span> &nbsp;
                        <strong>{{ $item->soal_count }}</strong>
                    </div>

                    <div class="flex justify-between mb-2">
                        <span>Durasi</span>  &nbsp;
                        <strong>{{ $item->durasi }} menit</strong>
                    </div>

                    <div class="flex justify-between">
                        <span>Nilai minimal</span>  &nbsp;
                        <strong>{{ $item->nilai_minimal }}</strong>
                    </div>
                </div>

                <div class="mt-5">
                    @if ($peserta?->status === 'selesai')
                        <a
                            href="{{ route(
                                'ujianCalon.hasil',
                                $peserta
                            ) }}"
                            class="btn btn-success w-full"
                        >
                            Lihat Hasil
                        </a>
                    @else
                       <form
                            action="{{ route('ujianCalon.mulai', ['ujian' => $item->id]) }}"
                            method="POST"
                            onsubmit="return confirm('Mulai ujian sekarang? Waktu akan langsung berjalan?')"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-full"
                            >
                                {{ $peserta?->status === 'sedang'
                                    ? 'Lanjutkan Ujian'
                                    : 'Mulai Ujian' }}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-12">
            <div class="box p-8 text-center text-slate-500">
                Belum ada ujian yang tersedia.
            </div>
        </div>
    @endforelse
</div>
@endsection
