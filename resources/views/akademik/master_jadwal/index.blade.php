@extends('layout.main')

@section('tittle')
Master Jadwal
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Master Jadwal</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">
    <ul class="nav nav-boxed-tabs" role="tablist">
        <li class="nav-item flex-1" role="presentation">
            <button
                class="nav-link w-full py-2 active"
                data-tw-toggle="pill"
                data-tw-target="#master-jadwal"
                type="button"
                role="tab"
                aria-controls="master-jadwal"
                aria-selected="true">

                Master Jadwal

            </button>
        </li>

        <li class="nav-item flex-1" role="presentation">
            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#detail-jadwal"
                type="button"
                role="tab"
                aria-controls="detail-jadwal"
                aria-selected="false">

                Detail Jadwal

            </button>
        </li>

    </ul>

    <div class="tab-content mt-5">

        <div
            id="master-jadwal"
            class="tab-pane leading-relaxed active"
            role="tabpanel">

            @include('akademik.master_jadwal.master-jadwal')

        </div>

        <div
            id="detail-jadwal"
            class="tab-pane leading-relaxed"
            role="tabpanel">

            @include('akademik.master_jadwal.detail-jadwal')

        </div>

    </div>

</div>

@include('akademik.master_jadwal.tab.add-master-jadwal')
@include('akademik.master_jadwal.tab.edit-master-jadwal')



@endsection