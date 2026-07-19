@extends('layout.main')

@section('tittle')
Jawaban Peserta Ujian
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('ujianCalonAdmin.index') }}">
            Ujian Calon Siswa
        </a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{ route('ujianCalonAdmin.peserta', $ujian->id) }}">
            Peserta
        </a>
    </li>

    <li class="breadcrumb-item active">
        Jawaban Peserta
    </li>
</ol>
@endsection

@section('body')

<div class="container-fluid">

    {{-- Informasi peserta --}}
    <div class="card mb-3">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-1">
                        {{ $ujian->nama_ujian }}
                    </h4>

                    <small class="text-muted">
                        Detail hasil dan jawaban peserta ujian
                    </small>
                </div>

                <a
                    href="{{ route('ujianCalonAdmin.peserta', $ujian->id) }}"
                    class="btn btn-secondary btn-sm"
                >
                    Kembali
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <tbody>
                        <tr>
                            <th width="200">Nama Peserta</th>
                            <td>
                                {{ $peserta->calonSiswa?->nama_lengkap ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>No. Daftar</th>
                            <td>
                                {{ $peserta->calonSiswa?->no_daftar ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Waktu Mulai</th>
                            <td>
                                {{ $peserta->waktu_mulai?->format('d-m-Y H:i') ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Waktu Selesai</th>
                            <td>
                                {{ $peserta->waktu_selesai?->format('d-m-Y H:i') ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-info">
                                    {{ ucfirst($peserta->status ?? '-') }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Nilai</th>
                            <td>
                                <strong>
                                    {{ number_format((float) $peserta->nilai, 2) }}
                                </strong>
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Benar</th>
                            <td>
                                <span class="badge bg-success">
                                    {{ $peserta->jumlah_benar ?? 0 }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Jumlah Salah</th>
                            <td>
                                <span class="badge bg-danger">
                                    {{ $peserta->jumlah_salah ?? 0 }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Tidak Dijawab</th>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $peserta->tidak_dijawab ?? 0 }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Hasil</th>
                            <td>
                                @if (strtolower($peserta->hasil ?? '') === 'lulus')
                                    <span class="badge bg-success">
                                        Lulus
                                    </span>
                                @elseif (strtolower($peserta->hasil ?? '') === 'tidak lulus')
                                    <span class="badge bg-danger">
                                        Tidak Lulus
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($peserta->hasil ?? '-') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Daftar jawaban --}}
    @forelse ($jawaban as $index => $item)

        @php
            $soal = $item->soal;

            $jawabanDipilih = $item->jawaban
                ? strtoupper($item->jawaban)
                : null;

            $jawabanBenar = $soal?->jawaban_benar
                ? strtoupper($soal->jawaban_benar)
                : null;

            $benar = (bool) $item->is_benar;
        @endphp

        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">

                <strong>
                    Soal {{ $index + 1 }}
                </strong>

                @if (!$jawabanDipilih)
                    <span class="badge bg-secondary">
                        Tidak Dijawab
                    </span>
                @elseif ($benar)
                    <span class="badge bg-success">
                        Benar
                    </span>
                @else
                    <span class="badge bg-danger">
                        Salah
                    </span>
                @endif

            </div>

            <div class="card-body">

                @if ($soal)

                    <p class="mb-3">
                        {!! nl2br(e($soal->pertanyaan)) !!}
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>

                                @foreach (['A', 'B', 'C', 'D', 'E'] as $pilihan)

                                    @php
                                        $field = 'pilihan_' . strtolower($pilihan);
                                        $isiPilihan = $soal->{$field};
                                    @endphp

                                    @if (!empty($isiPilihan))
                                        <tr
                                            @class([
                                                'table-success' => $jawabanBenar === $pilihan,
                                                'table-danger' =>
                                                    $jawabanDipilih === $pilihan
                                                    && $jawabanBenar !== $pilihan,
                                            ])
                                        >
                                            <td width="60">
                                                <strong>{{ $pilihan }}</strong>
                                            </td>

                                            <td>
                                                {{ $isiPilihan }}

                                                @if ($jawabanDipilih === $pilihan)
                                                    <span class="badge bg-primary ms-2">
                                                        Jawaban Peserta
                                                    </span>
                                                @endif

                                                @if ($jawabanBenar === $pilihan)
                                                    <span class="badge bg-success ms-2">
                                                        Jawaban Benar
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <table class="table table-sm table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th width="200">Jawaban Peserta</th>
                                    <td>
                                        {{ $jawabanDipilih ?? 'Tidak dijawab' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Jawaban Benar</th>
                                    <td>
                                        {{ $jawabanBenar ?? '-' }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Bobot</th>
                                    <td>
                                        {{ $item->bobot ?? $soal->bobot ?? 0 }}
                                    </td>
                                </tr>

                                <tr>
                                    <th>Hasil Jawaban</th>
                                    <td>
                                        @if (!$jawabanDipilih)
                                            <span class="badge bg-secondary">
                                                Tidak Dijawab
                                            </span>
                                        @elseif ($benar)
                                            <span class="badge bg-success">
                                                Benar
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Salah
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="alert alert-warning mb-0">
                        Data soal sudah tidak ditemukan.
                    </div>

                @endif

            </div>
        </div>

    @empty

        <div class="alert alert-info">
            Jawaban peserta belum tersedia.
        </div>

    @endforelse

</div>

@endsection