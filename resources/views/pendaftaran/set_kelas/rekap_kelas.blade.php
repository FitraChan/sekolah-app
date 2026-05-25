@extends('layout.main')

@section('tittle')
Rekap Kelas
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">
        Rekap Kelas
    </li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <!-- HEADER -->
                <div class="flex items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">

                        Data Rekap Kelas

                    </h2>

                </div>

                <!-- TABLE -->
                <div class="p-5">

                    <div class="overflow-x-auto">

                        <table class="table table-bordered table-hover">

                            <thead>

                                <tr>

                                    <th class="text-center w-20">
                                        No
                                    </th>

                                    <th>
                                        Kelas
                                    </th>

                                    <th>
                                        Jurusan
                                    </th>

                                    <th class="text-center">
                                        Jumlah Siswa
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($data as $row)

                                <tr>

                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $row->nama_kelas }}
                                    </td>

                                    <td>
                                        {{ $row->nama_jurusan }}
                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-primary text-white px-3 py-2">

                                            {{ $row->jml_siswa }} Siswa

                                        </span>

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="4" class="text-center text-slate-500">

                                        Data tidak ditemukan

                                    </td>

                                </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection