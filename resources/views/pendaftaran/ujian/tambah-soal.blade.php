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
        Tambah Soal
    </h2>
</div>

<div class="intro-y box p-5 mt-5">
    <form
        action="{{ route('soalCalon.store', ['ujian' => $ujian->id]) }}"
        method="POST"
    >
        @csrf

        <div class="mb-5">
            <label class="form-label">Pertanyaan</label>

            <textarea
                name="pertanyaan"
                class="form-control"
                rows="5"
                required
            >{{ old('pertanyaan') }}</textarea>
        </div>

        @foreach (['A', 'B', 'C', 'D', 'E'] as $pilihan)
            @php
                $field = 'pilihan_' . strtolower($pilihan);
            @endphp

            <div class="mb-4">
                <label class="form-label">
                    Pilihan {{ $pilihan }}
                </label>

                <textarea
                    name="{{ $field }}"
                    class="form-control"
                    rows="2"
                    @required($pilihan !== 'E')
                >{{ old($field) }}</textarea>
            </div>
        @endforeach

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-12 md:col-span-6">
                <label class="form-label">Jawaban Benar</label>

                <select
                    name="jawaban_benar"
                    class="form-control"
                    required
                >
                    <option value="">Pilih jawaban</option>

                    @foreach (['A', 'B', 'C', 'D', 'E'] as $pilihan)
                        <option
                            value="{{ $pilihan }}"
                            @selected(old('jawaban_benar') === $pilihan)
                        >
                            {{ $pilihan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label">Bobot</label>

                <input
                    type="number"
                    name="bobot"
                    class="form-control"
                    value="{{ old('bobot', 1) }}"
                    min="0.01"
                    step="0.01"
                    required
                >
            </div>
        </div>

        <label class="flex items-center mt-5">
            <input
                type="checkbox"
                name="status"
                value="1"
                class="form-check-input"
                @checked(old('status', true))
            >

            <span class="ml-2">Soal aktif</span>
        </label>

        <div class="mt-6 flex gap-2">
            <button type="submit" class="btn btn-primary">
                Simpan Soal
            </button>

            <a
                href="{{ route('soalCalon.index', $ujian) }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
