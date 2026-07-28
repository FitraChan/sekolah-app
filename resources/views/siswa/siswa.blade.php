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

    {{-- NAV TAB --}}
    <ul class="nav nav-boxed-tabs" role="tablist">

        {{-- DATA SISWA --}}
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

                <i data-lucide="user"
                    class="w-4 h-4 mr-2"></i>

                Data Siswa

            </button>

        </li>

        {{-- ORANG TUA --}}
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

                <i data-lucide="users"
                    class="w-4 h-4 mr-2"></i>

                Orang Tua

            </button>

        </li>

        {{-- UPLOAD DOKUMEN --}}
        <li
            id="upload-siswa-tab"
            class="nav-item flex-1"
            role="presentation">

            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#upload-siswa-content"
                type="button"
                role="tab"
                aria-controls="upload-siswa-content"
                aria-selected="false">

                <i data-lucide="upload-cloud"
                    class="w-4 h-4 mr-2"></i>

                Upload Dokumen

            </button>

        </li>

    </ul>

    {{-- TAB CONTENT --}}
    <div class="tab-content mt-5">

        {{-- TAB DATA SISWA --}}
        <div
            id="data-siswa-content"
            class="tab-pane leading-relaxed active"
            role="tabpanel"
            aria-labelledby="data-siswa-tab">

            @include('siswa.tab.data_siswa')

        </div>

        {{-- TAB ORANG TUA --}}
        <div
            id="orang-tua-siswa-content"
            class="tab-pane leading-relaxed"
            role="tabpanel"
            aria-labelledby="orang-tua-siswa-tab">

            @include('siswa.tab.orang_tua')

        </div>

        {{-- TAB UPLOAD DOKUMEN --}}
        <div
            id="upload-siswa-content"
            class="tab-pane leading-relaxed"
            role="tabpanel"
            aria-labelledby="upload-siswa-tab">

            @include('siswa.tab.upload')

        </div>

    </div>

</div>

@endsection