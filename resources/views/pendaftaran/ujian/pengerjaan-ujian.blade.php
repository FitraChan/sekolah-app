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
<div class="intro-y flex flex-col md:flex-row md:items-center mt-8">
    <div class="mr-auto">
        <h2 class="text-lg font-medium">
            {{ $peserta->ujian->nama_ujian }}
        </h2>

        <div class="text-slate-500 mt-1">
            Pilih satu jawaban yang paling benar.
        </div>
    </div>

    <div class="box px-5 py-3 mt-3 md:mt-0">
        Sisa waktu:
        <strong id="timer" class="text-danger">
            00:00
        </strong>
    </div>
</div>

<form
    id="form-ujian"
    action="{{ route('calon-siswa.ujian.submit', $peserta) }}"
    method="POST"
>
    @csrf

    <div class="mt-5">
        @foreach ($soal as $item)
            <div class="intro-y box p-5 mb-5">
                <div class="font-medium text-lg">
                    {{ $loop->iteration }}.
                    {!! nl2br(e($item->pertanyaan)) !!}
                </div>

                <div class="mt-5 space-y-3">
                    @foreach (['A', 'B', 'C', 'D', 'E'] as $pilihan)
                        @php
                            $field = 'pilihan_' . strtolower($pilihan);
                        @endphp

                        @if ($item->$field)
                            <label
                                class="flex items-start border rounded p-3 cursor-pointer hover:bg-slate-50"
                            >
                                <input
                                    type="radio"
                                    name="jawaban[{{ $item->id }}]"
                                    value="{{ $pilihan }}"
                                    class="form-check-input mt-1"
                                >

                                <span class="ml-3">
                                    <strong>{{ $pilihan }}.</strong>
                                    {{ $item->$field }}
                                </span>
                            </label>
                        @endif
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex justify-end mb-10">
        <button
            type="submit"
            class="btn btn-primary"
            onclick="return confirm(
                'Apakah Anda yakin ingin menyelesaikan ujian?'
            )"
        >
            Selesai dan Kirim Jawaban
        </button>
    </div>
</form>

<script>
    const batasWaktu = new Date(
        @json($batasWaktu->toIso8601String())
    ).getTime();

    const timerElement = document.getElementById('timer');
    const formUjian = document.getElementById('form-ujian');

    const timer = setInterval(function () {
        const sekarang = new Date().getTime();
        const selisih = batasWaktu - sekarang;

        if (selisih <= 0) {
            clearInterval(timer);
            timerElement.textContent = '00:00';

            alert('Waktu ujian telah habis. Jawaban akan dikirim.');
            formUjian.submit();
            return;
        }

        const jam = Math.floor(selisih / (1000 * 60 * 60));
        const menit = Math.floor(
            (selisih % (1000 * 60 * 60)) / (1000 * 60)
        );
        const detik = Math.floor(
            (selisih % (1000 * 60)) / 1000
        );

        timerElement.textContent =
            String(jam).padStart(2, '0') + ':' +
            String(menit).padStart(2, '0') + ':' +
            String(detik).padStart(2, '0');
    }, 1000);
</script>

@endsection
