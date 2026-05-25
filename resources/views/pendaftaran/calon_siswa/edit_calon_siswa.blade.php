@extends('layout.main')

@section('tittle')
Calon Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Calon Siswa</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">
    <ul class="nav nav-boxed-tabs" role="tablist">
        <li id="siswa-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2 active" data-tw-toggle="pill" data-tw-target="#siswa-tab" type="button" role="tab" aria-controls="siswa-tab" aria-selected="true"> Data Calon Siswa </button> </li>
        <li id="registrasi-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#registrasi-tab" type="button" role="tab" aria-controls="registrasi-tab" aria-selected="false"> Registrasi </button> </li>
        <li id="orang-tua-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#orang-tua-tab" type="button" role="tab" aria-controls="orang-tua-tab" aria-selected="false"> Orang Tua </button> </li>

        <li id="bukti-tab" class="nav-item flex-1" role="presentation"> <button class="nav-link w-full py-2" data-tw-toggle="pill" data-tw-target="#bukti-tab" type="button" role="tab" aria-controls="bukti-tab" aria-selected="false"> Bukti Registrasi </button> </li>

    </ul>
    <div class="tab-content mt-5">
        <div id="siswa-tab" class="tab-pane leading-relaxed active" role="tabpanel" aria-labelledby="siswa-tab"> @include('pendaftaran.calon_siswa.tab.data_calon_siswa') </div>
        <div id="registrasi-tab" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="registrasi-tab"> @include('pendaftaran.calon_siswa.tab.registrasi') </div>
        <div id="orang-tua-tab" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="orang-tua-tab"> @include('pendaftaran.calon_siswa.tab.orang_tua') </div>
        <div id="bukti-tab" class="tab-pane leading-relaxed" role="tabpanel" aria-labelledby="bukti-tab"> @include('pendaftaran.calon_siswa.tab.bukti') </div>
    </div>
</div>

@endsection