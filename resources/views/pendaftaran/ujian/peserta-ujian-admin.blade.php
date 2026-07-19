@extends('layout.main')

@section('tittle')
Hasil Ujian Calon Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('ujianCalonAdmin.index') }}">
            Ujian Calon Siswa
        </a>
    </li>

    <li class="breadcrumb-item active">
        Hasil Ujian
    </li>
</ol>
@endsection

@section('body')

<div class="intro-y flex items-center mt-8">
    <div class="mr-auto">
        <h2 class="text-lg font-medium">
            Hasil Ujian Calon Siswa
        </h2>

        <div class="text-slate-500 mt-1">
            {{ $ujian->nama_ujian }}
        </div>
    </div>

    <a href="{{ route('ujianCalonAdmin.index') }}"
       class="btn btn-secondary">
        Kembali
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success mt-5">
        {{ session('success') }}
    </div>
@endif

<div class="intro-y box p-5 mt-5">

    {{-- Informasi ujian --}}
    <div class="grid grid-cols-12 gap-4 mb-5">

        <div class="col-span-12 md:col-span-4">
            <div class="text-slate-500 text-xs">
                Nama Ujian
            </div>

            <div class="font-medium mt-1">
                {{ $ujian->nama_ujian }}
            </div>
        </div>

        <div class="col-span-12 md:col-span-3">
            <div class="text-slate-500 text-xs">
                Tanggal Ujian
            </div>

            <div class="font-medium mt-1">
                {{ $ujian->tanggal_mulai?->locale('id')->translatedFormat('d F Y') ?? '-' }}
            </div>
        </div>

        <div class="col-span-12 md:col-span-2">
            <div class="text-slate-500 text-xs">
                Durasi
            </div>

            <div class="font-medium mt-1">
                {{ $ujian->durasi }} menit
            </div>
        </div>

        <div class="col-span-12 md:col-span-3">
            <div class="text-slate-500 text-xs">
                Nilai Minimal
            </div>

            <div class="font-medium mt-1">
                {{ number_format((float) $ujian->nilai_minimal, 0) }}
            </div>
        </div>

    </div>

    <div class="overflow-x-auto">
        <table class="table table-report">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. Daftar</th>
                    <th>Nama Peserta</th>
                    <th>Waktu Mulai</th>
                    <th>Waktu Selesai</th>
                    <th class="text-center">Benar</th>
                    <th class="text-center">Salah</th>
                    <th class="text-center">Kosong</th>
                    <th class="text-center">Nilai</th>
                    <th class="text-center">Hasil</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($data as $item)
                    <tr>
                        <td>
                            {{ $data->firstItem() + $loop->index }}
                        </td>

                        <td>
                            {{ $item->calonSiswa?->no_daftar ?? '-' }}
                        </td>

                        <td>
                            <div class="font-medium whitespace-nowrap">
                                {{ $item->calonSiswa?->nama_lengkap ?? '-' }}
                            </div>
                        </td>

                        <td class="whitespace-nowrap">
                            {{ $item->waktu_mulai?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap">
                            {{ $item->waktu_selesai?->format('d-m-Y H:i') ?? '-' }}
                        </td>

                        <td class="text-center">
                            <span class="text-success font-medium">
                                {{ $item->jumlah_benar ?? 0 }}
                            </span>
                        </td>

                        <td class="text-center">
                            <span class="text-danger font-medium">
                                {{ $item->jumlah_salah ?? 0 }}
                            </span>
                        </td>

                        <td class="text-center">
                            {{ $item->tidak_dijawab ?? 0 }}
                        </td>

                        <td class="text-center">
                            <div class="font-medium">
                                {{ number_format((float) ($item->nilai ?? 0), 2) }}
                            </div>
                        </td>

                        <td class="text-center">
                            @php
                                $hasil = strtolower(trim($item->hasil ?? ''));

                                $lulus = $hasil === 'lulus'
                                    || (float) $item->nilai >= (float) $ujian->nilai_minimal;
                            @endphp

                            @if ($item->status !== 'selesai')
                                <span class="text-warning">
                                    Belum Selesai
                                </span>
                            @elseif ($lulus)
                                <span class="text-success font-medium">
                                    Lulus
                                </span>
                            @else
                                <span class="text-danger font-medium">
                                    Tidak Lulus
                                </span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a
                                href="{{ route('ujianCalonAdmin.jawaban-peserta', [
                                    'ujianCalonAdmin' => $ujian->id,
                                    'peserta' => $item->id,
                                ]) }}"
                                class="btn btn-sm btn-primary whitespace-nowrap"
                            >
                                Lihat Jawaban
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center py-5">
                            Belum ada calon siswa yang mengikuti ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $data->links() }}
    </div>

</div>

@endsection