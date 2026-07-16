@extends('layout.main')

@section('tittle')
Dashboard
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="#">Dashboard</a>
    </li>
</ol>
@endsection

@section('body')

<div class="col-span-12 mt-8">

    <div class="intro-y flex items-center h-10">
        <h2 class="text-lg font-medium truncate mr-5">
            Dashboard
        </h2>

        <a href="{{ url()->current() }}"
           class="ml-auto flex items-center text-primary">

            <i data-lucide="refresh-ccw"
               class="w-4 h-4 mr-3"></i>

            Reload Data
        </a>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

    {{-- Jumlah Pendaftar --}}
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">

                <div class="flex">
                    <i data-lucide="users"
                       class="report-box__icon text-primary"></i>

                    <div class="ml-auto">
                        <div class="report-box__indicator bg-success tooltip cursor-pointer"
                             title="Pendaftar dengan status sudah diproses">
                            Aktif
                            <i data-lucide="chevron-up"
                               class="w-4 h-4 ml-0.5"></i>
                        </div>
                    </div>
                </div>

                <div class="text-3xl font-medium leading-8 mt-6">
                    {{ number_format($jdaftar->jumlah ?? 0, 0, ',', '.') }}
                </div>

                <div class="text-base text-slate-500 mt-1">
                    Jumlah Pendaftar
                </div>

            </div>
        </div>
    </div>

    {{-- Belum Diverifikasi --}}
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">

                <div class="flex">
                    <i data-lucide="user-x"
                       class="report-box__icon text-pending"></i>

                    <div class="ml-auto">
                        <div class="report-box__indicator bg-danger tooltip cursor-pointer"
                             title="Pendaftar yang belum diverifikasi">
                            Pending
                            <i data-lucide="chevron-down"
                               class="w-4 h-4 ml-0.5"></i>
                        </div>
                    </div>
                </div>

                <div class="text-3xl font-medium leading-8 mt-6">
                    {{ number_format($daftar->jumlah ?? 0, 0, ',', '.') }}
                </div>

                <div class="text-base text-slate-500 mt-1">
                    Belum Diverifikasi
                </div>

            </div>
        </div>
    </div>

    {{-- Registrasi Ulang --}}
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">

                <div class="flex">
                    <i data-lucide="clipboard-check"
                       class="report-box__icon text-warning"></i>

                    <div class="ml-auto">
                        <div class="report-box__indicator bg-success tooltip cursor-pointer"
                             title="Pendaftar yang sudah registrasi ulang">
                            Selesai
                            <i data-lucide="check"
                               class="w-4 h-4 ml-0.5"></i>
                        </div>
                    </div>
                </div>

                <div class="text-3xl font-medium leading-8 mt-6">
                    {{ number_format($jregis->jumlah ?? 0, 0, ',', '.') }}
                </div>

                <div class="text-base text-slate-500 mt-1">
                    Registrasi Ulang
                </div>

            </div>
        </div>
    </div>

    {{-- Total Seluruh Data --}}
    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
        <div class="report-box zoom-in">
            <div class="box p-5">

                <div class="flex">
                    <i data-lucide="database"
                       class="report-box__icon text-success"></i>

                    <div class="ml-auto">
                        <div class="report-box__indicator bg-primary tooltip cursor-pointer"
                             title="Total seluruh data calon siswa">
                            Total
                            <i data-lucide="bar-chart-3"
                               class="w-4 h-4 ml-0.5"></i>
                        </div>
                    </div>
                </div>

                <div class="text-3xl font-medium leading-8 mt-6">
                    {{
                        number_format(
                            ($jdaftar->jumlah ?? 0) + ($daftar->jumlah ?? 0),
                            0,
                            ',',
                            '.'
                        )
                    }}
                </div>

                <div class="text-base text-slate-500 mt-1">
                    Total Calon Siswa
                </div>

            </div>
        </div>
    </div>

</div>

</div>


{{-- TABEL TARGET PENDAFTARAN PALING BAWAH --}}
<div class="col-span-12 mt-8 intro-y">

    <div class="intro-y box">

        <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

            <h2 class="font-medium text-base mr-auto">
                Target Penerimaan Siswa
            </h2>

        </div>

        <div class="p-5">

            <div class="overflow-x-auto">

                <table class="table table-report">

                    <thead>
                        <tr>
                            <th class="whitespace-nowrap text-center">
                                No
                            </th>

                            <th class="whitespace-nowrap">
                                Tahun Ajaran
                            </th>

                            <th class="whitespace-nowrap">
                                Jurusan
                            </th>

                            <th class="whitespace-nowrap text-center">
                                Target
                            </th>

                            <th class="whitespace-nowrap text-center">
                                Pendaftar
                            </th>

                            <th class="whitespace-nowrap text-center">
                                Registrasi
                            </th>

                            <th class="whitespace-nowrap text-center">
                                Batal
                            </th>

                            <th class="whitespace-nowrap text-center">
                                Pencapaian
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($data as $index => $item)

                            @php
                                $target = (int) ($item['target'] ?? 0);
                                $registrasi = (int) ($item['jml_regis'] ?? 0);

                                $persentase = $target > 0
                                    ? ($registrasi / $target) * 100
                                    : 0;

                                $persentase = min($persentase, 100);
                            @endphp

                            <tr class="intro-x">

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $item['thn_ajaran'] ?? '-' }}
                                </td>

                                <td>
                                    {{ $item['nama_jurusan'] ?? '-' }}
                                </td>

                                <td class="text-center font-medium">
                                    {{ number_format($target, 0, ',', '.') }}
                                </td>

                                <td class="text-center">

                                <?php  $jml = $item['jml_daftar'] +  $item['jml_regis'];?>   
                                    {{ number_format($jml ?? 0, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    <span class="px-2 py-1 rounded-full bg-success/20 text-success">
                                        {{ number_format($registrasi, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="px-2 py-1 rounded-full bg-danger/20 text-danger">
                                        {{ number_format($item['jml_batal'] ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-center">

                                    <div class="flex items-center">

                                        <div class="w-full bg-slate-200 rounded-full h-3">

                                            <div
                                                class="bg-primary h-3 rounded-full"
                                                style="width: {{ $persentase }}%">
                                            </div>

                                        </div>

                                        <div class="ml-3 whitespace-nowrap font-medium">
                                            {{ number_format($persentase, 1, ',', '.') }}%
                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8"
                                    class="text-center py-8 text-slate-500">

                                    Data target belum tersedia.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- TABEL RINGKASAN PEMBAYARAN --}}
<div class="col-span-12 mt-8 intro-y">

    <div class="intro-y box">

        <div class="flex items-center p-5 border-b border-slate-200/60">

            <h2 class="font-medium text-base mr-auto">
                Ringkasan Pembayaran Siswa
            </h2>

        </div>

        <div class="p-5">

            <div class="overflow-x-auto">

                <table class="table table-report table-hover">

                    <thead>

                        <tr>

                            <th class="text-center">No</th>

                            <th>Tahun Ajaran</th>

                            <th class="text-right">Kewajiban</th>

                            <th class="text-right">Potongan</th>

                            <th class="text-right">Dibayar</th>

                            <th class="text-right">Tunggakan</th>

							<th class="text-right">Belum Bayar</th>

                            <th class="text-center">Progress</th>


                        </tr>

                    </thead>

                    <tbody>

                        @forelse($hasil as $index => $item)

                            @php

                                $kewajiban = $item->SUMK2 ?? 0;
                                $potongan  = $item->SUMP ?? 0;
                                $bayar     = $item->SUMB ?? 0;

                                $total = max($kewajiban - $potongan,0);

                                $sisa = max($total - $bayar,0);

                                $progress = $total > 0
                                    ? ($bayar / $total) * 100
                                    : 0;

                                $progress = min($progress,100);

                            @endphp

                            <tr>

                                <td class="text-center">
                                    {{ $index+1 }}
                                </td>

                                <td>
                                    {{ $item->thn_ajaran }}
                                </td>

                                <td class="text-right">
                                    Rp {{ number_format($total,0,',','.') }}
                                </td>

                                <td class="text-right text-warning">
                                    Rp {{ number_format($potongan,0,',','.') }}
                                </td>

                                <td class="text-right text-success font-medium">
                                    Rp {{ number_format($bayar,0,',','.') }}
                                </td>

                                <td class="text-right">

                                    <span class="{{ $sisa==0 ? 'text-success' : 'text-danger' }} font-medium">

                                        Rp {{ number_format($sisa,0,',','.') }}

                                    </span>

                                </td>



                                <td class="text-right text-danger font-medium">
                                  {{ $item->jml_belum_bayar }} siswa
                                </td>


                                <td width="220">

                                    <div class="flex items-center">

                                        <div class="w-full bg-slate-200 rounded-full h-2">

                                            <div
                                                class="bg-primary h-2 rounded-full"
                                                style="width:{{ $progress }}%">
                                            </div>

                                        </div>

                                        <div class="ml-3">

                                            {{ number_format($progress,1) }}%

                                        </div>

                                    </div>

                                </td>

                               

                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center text-slate-500 py-5">

                                    Belum ada data pembayaran.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                    @if($hasil->count())

                    <tfoot class="bg-slate-100 font-bold">

                        @php

                            $totalKewajiban = $hasil->sum(function($r){
                                return ($r->SUMK2 ?? 0) - ($r->SUMP ?? 0);
                            });

                            $totalBayar = $hasil->sum('SUMB');

                            $totalPotongan = $hasil->sum('SUMP');

                            $totalSisa = max(
                                $totalKewajiban - $totalBayar,
                                0
                            );

                        @endphp

                        <tr>

                            <td colspan="2" class="text-right">
                                TOTAL
                            </td>

                            <td class="text-right">
                                Rp {{ number_format($totalKewajiban,0,',','.') }}
                            </td>

                            <td class="text-right">
                                Rp {{ number_format($totalPotongan,0,',','.') }}
                            </td>

                            <td class="text-right text-success">
                                Rp {{ number_format($totalBayar,0,',','.') }}
                            </td>

                            <td class="text-right text-danger">
                                Rp {{ number_format($totalSisa,0,',','.') }}
                            </td>

                            <td colspan="3"></td>

                        </tr>

                    </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endsection