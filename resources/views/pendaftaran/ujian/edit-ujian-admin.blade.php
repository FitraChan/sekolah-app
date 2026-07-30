@extends('layout.main')

@section('tittle')
Edit Ujian
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('ujianCalonAdmin.index') }}">
            Ujian Calon
        </a>
    </li>

    <li class="breadcrumb-item active" aria-current="page">
        Edit Ujian
    </li>
</ol>
@endsection

@section('body')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Edit Ujian
    </h2>
</div>

<div class="intro-y box p-5 mt-5">

    <form
    action="{{ route('ujianCalonAdmin.update', [
        'ujianCalonAdmin' => $ujian
    ]) }}"
    method="POST"
>
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="form-label">
                Nama Ujian
            </label>

            <input
                type="text"
                name="nama_ujian"
                class="form-control @error('nama_ujian') border-danger @enderror"
                value="{{ old('nama_ujian', $ujian->nama_ujian) }}"
                required
            >

            @error('nama_ujian')
                <div class="text-danger mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-4">
    <label class="form-label">
        Gelombang
    </label>

    <select
        name="id_gelombang"
        class="form-select @error('id_gelombang') border-danger @enderror"
        required
    >
        <option value="">
            -- Pilih Gelombang --
        </option>

        @foreach ($gelombang as $item)
            <option
                value="{{ $item->id }}"
                @selected(
                    old('id_gelombang', $ujian->id_gelombang)
                    == $item->id
                )
            >
                {{ $item->nama_gelombang }}
            </option>
        @endforeach
    </select>

    @error('id_gelombang')
        <div class="text-danger mt-1">
            {{ $message }}
        </div>
    @enderror
</div>

        <div class="mb-4">
            <label class="form-label">
                Deskripsi
            </label>

            <textarea
                name="deskripsi"
                class="form-control @error('deskripsi') border-danger @enderror"
                rows="4"
            >{{ old('deskripsi', $ujian->deskripsi) }}</textarea>

            @error('deskripsi')
                <div class="text-danger mt-1">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="grid grid-cols-12 gap-4">

            <div class="col-span-12 md:col-span-6">
                <label class="form-label">
                    Tanggal Mulai
                </label>

                <input
                    type="datetime-local"
                    name="tanggal_mulai"
                    class="form-control @error('tanggal_mulai') border-danger @enderror"
                    value="{{ old(
                        'tanggal_mulai',
                        optional($ujian->tanggal_mulai)->format('Y-m-d\TH:i')
                    ) }}"
                >

                @error('tanggal_mulai')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label">
                    Tanggal Selesai
                </label>

                <input
                    type="datetime-local"
                    name="tanggal_selesai"
                    class="form-control @error('tanggal_selesai') border-danger @enderror"
                    value="{{ old(
                        'tanggal_selesai',
                        optional($ujian->tanggal_selesai)->format('Y-m-d\TH:i')
                    ) }}"
                >

                @error('tanggal_selesai')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label">
                    Durasi
                </label>

                <div class="input-group">
                    <input
                        type="number"
                        name="durasi"
                        class="form-control @error('durasi') border-danger @enderror"
                        value="{{ old('durasi', $ujian->durasi) }}"
                        min="1"
                        required
                    >

                    <div class="input-group-text">
                        Menit
                    </div>
                </div>

                @error('durasi')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="col-span-12 md:col-span-6">
                <label class="form-label">
                    Nilai Minimal
                </label>

                <input
                    type="number"
                    name="nilai_minimal"
                    class="form-control @error('nilai_minimal') border-danger @enderror"
                    value="{{ old('nilai_minimal', $ujian->nilai_minimal) }}"
                    min="0"
                    max="100"
                    required
                >

                @error('nilai_minimal')
                    <div class="text-danger mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        </div>

        <div class="mt-5">

            <label class="flex items-center mb-3">
                <input
                    type="checkbox"
                    name="acak_soal"
                    value="1"
                    class="form-check-input"
                    @checked(old('acak_soal', $ujian->acak_soal))
                >

                <span class="ml-2">
                    Acak urutan soal
                </span>
            </label>

            <label class="flex items-center mb-3">
                <input
                    type="checkbox"
                    name="tampil_hasil"
                    value="1"
                    class="form-check-input"
                    @checked(old('tampil_hasil', $ujian->tampil_hasil))
                >

                <span class="ml-2">
                    Tampilkan hasil kepada peserta
                </span>
            </label>

            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="status"
                    value="1"
                    class="form-check-input"
                    @checked(old('status', $ujian->status))
                >

                <span class="ml-2">
                    Aktifkan ujian
                </span>
            </label>

        </div>

        <div class="mt-6 flex gap-2">

            <button
                type="submit"
                class="btn btn-primary"
            >
                Update
            </button>

            <a
                href="{{ route('ujianCalonAdmin.index') }}"
                class="btn btn-secondary"
            >
                Kembali
            </a>

        </div>

    </form>

</div>

@endsection