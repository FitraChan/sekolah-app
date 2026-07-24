@extends('layout.main')

@section('tittle')
Data Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        <a href="{{ route('siswa.index') }}">
            Data Siswa
        </a>
    </li>

    <li class="breadcrumb-item active">
        {{ $rows->exists ? 'Edit Siswa' : 'Tambah Siswa' }}
    </li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    {{-- Pesan berhasil --}}
    @if (session('success'))
        <div class="alert alert-success mb-5">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan error --}}
    @if (session('error'))
        <div class="alert alert-danger mb-5">
            {{ session('error') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-5">
            <div class="font-medium mb-2">
                Data belum dapat disimpan:
            </div>

            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <ul class="nav nav-boxed-tabs" role="tablist">

        <li
            id="data-siswa-tab"
            class="nav-item flex-1"
            role="presentation">

            <button
                class="nav-link w-full py-2 active"
                data-tw-toggle="pill"
                data-tw-target="#data-siswa-content"
                type="button"
                role="tab"
                aria-controls="data-siswa-content"
                aria-selected="true">

                Data Siswa

            </button>
        </li>

        <li
            id="akademik-siswa-tab"
            class="nav-item flex-1"
            role="presentation">

            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#akademik-siswa-content"
                type="button"
                role="tab"
                aria-controls="akademik-siswa-content"
                aria-selected="false">

                Data Akademik

            </button>
        </li>

        <li
            id="orang-tua-siswa-tab"
            class="nav-item flex-1"
            role="presentation">

            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#orang-tua-siswa-content"
                type="button"
                role="tab"
                aria-controls="orang-tua-siswa-content"
                aria-selected="false">

                Orang Tua

            </button>
        </li>

        <li
            id="akun-siswa-tab"
            class="nav-item flex-1"
            role="presentation">

            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#akun-siswa-content"
                type="button"
                role="tab"
                aria-controls="akun-siswa-content"
                aria-selected="false">

                Akun

            </button>
        </li>

    </ul>

    <div class="tab-content mt-5">

        {{-- Tab data siswa --}}
        <div
            id="data-siswa-content"
            class="tab-pane leading-relaxed active"
            role="tabpanel"
            aria-labelledby="data-siswa-tab">

            @include('siswa.tab.data_siswa')

        </div>

        {{-- Tab data akademik --}}
        <div
            id="akademik-siswa-content"
            class="tab-pane leading-relaxed"
            role="tabpanel"
            aria-labelledby="akademik-siswa-tab">

            @include('siswa.tab.akademik')

        </div>

        {{-- Tab orang tua --}}
        <div
            id="orang-tua-siswa-content"
            class="tab-pane leading-relaxed"
            role="tabpanel"
            aria-labelledby="orang-tua-siswa-tab">

            @include('siswa.tab.orang_tua')

        </div>

        {{-- Tab akun --}}
        <div
            id="akun-siswa-content"
            class="tab-pane leading-relaxed"
            role="tabpanel"
            aria-labelledby="akun-siswa-tab">

            @include('siswa.tab.akun')

        </div>

    </div>

</div>

@endsection