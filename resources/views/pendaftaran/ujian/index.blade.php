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
        Data Ujian Calon Siswa
    </h2>

    <a href="{{ route('ujianCalonAdmin.create') }}"
       class="btn btn-primary">
        Tambah Ujian
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success mt-5">
        {{ session('success') }}
    </div>
@endif

<div class="intro-y box p-5 mt-5 overflow-x-auto">
    <table class="table table-report">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Ujian</th>
                <th>Durasi</th>
                <th>Nilai Minimal</th>
                <th>Jumlah Soal</th>
                <th>Peserta</th>
                <th>Status</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($data as $item)
                <tr>
                    <td>
                        {{ $data->firstItem() + $loop->index }}
                    </td>

                    <td>
                        <div class="font-medium">
                            {{ $item->nama_ujian }}
                        </div>

                        <div class="text-slate-500 text-xs mt-1">
                            {{ Str::limit($item->deskripsi, 60) }}
                        </div>
                    </td>

                    <td>{{ $item->durasi }} menit</td>

                    <td>
                        {{ number_format($item->nilai_minimal, 0) }}
                    </td>

                    <td>{{ $item->soal_count }}</td>

                    <td>{{ $item->peserta_count }}</td>

                    <td>
                        @if ($item->status)
                            <span class="text-success">
                                Aktif
                            </span>
                        @else
                            <span class="text-danger">
                                Tidak aktif
                            </span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('soalCalon.index', $item) }}"
                               class="btn btn-sm btn-primary">
                                Soal
                            </a>

                            <a href="{{ route('ujianCalonAdmin.edit', $item) }}"
                               class="btn btn-sm btn-warning">
                                Edit
                            </a>

                              <a href="{{ route('ujianCalonAdmin.peserta', $item) }}"
                                class="btn btn-sm btn-success">
                                    Hasil Ujian
                                </a>

                            <form
                                action="{{ route('ujianCalonAdmin.destroy', $item) }}"
                                method="POST"
                                onsubmit="return confirm('Hapus ujian ini?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-danger"
                                >
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        Belum ada data ujian.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-5">

     {{ $data->links()  }}
       
    </div>
</div>



@endsection