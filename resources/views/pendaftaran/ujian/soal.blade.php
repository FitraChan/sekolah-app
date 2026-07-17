@extends('layout.main')

@section('tittle')
Target Pendaftaran
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Soal</li>
</ol>
@endsection

@section('body')

<div class="intro-y flex items-center mt-8">
    <div class="mr-auto">
        <h2 class="text-lg font-medium">
            Soal {{ $ujian->nama_ujian }}
        </h2>

        <div class="text-slate-500 mt-1">
            Durasi {{ $ujian->durasi }} menit
        </div>
    </div>

    <a
        href="{{ route('soalCalon.create', ['ujian' => $ujian->id]) }}"
        class="btn btn-primary">
        Tambah Soal
    </a>
</div>

@if (session('success'))
<div class="alert alert-success mt-5">
    {{ session('success') }}
</div>
@endif

<div class="intro-y box p-5 mt-5">
    @forelse ($soal as $item)
    <div class="border-b pb-5 mb-5">
        <div class="flex justify-between">
            <div class="font-medium">
                Soal {{ $soal->firstItem() + $loop->index }}
            </div>

            <div class="flex items-center justify-end gap-2 ml-auto"> <a
                    href="{{ route('soalCalon.edit', ['soal' => $item->id]) }}"
                    class="btn btn-sm btn-warning">
                    Edit
                </a>

                <form
                    action="{{ route('soalCalon.destroy', [
        'soal' => $item->id
    ]) }}"
                    method="POST"
                    onsubmit="return confirm('Hapus soal ini?')">
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-sm btn-danger">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-3">
            {!! nl2br(e($item->pertanyaan)) !!}
        </div>

        <div class="grid grid-cols-12 gap-2 mt-4">
            @foreach (['A', 'B', 'C', 'D', 'E'] as $pilihan)
            @php
            $field = 'pilihan_' . strtolower($pilihan);
            @endphp

            @if ($item->$field)
            <div class="col-span-12 md:col-span-6">
                <div class="border rounded p-3
                                {{ $item->jawaban_benar === $pilihan
                                    ? 'border-success text-success'
                                    : '' }}">
                    <strong>{{ $pilihan }}.</strong>
                    {{ $item->$field }}
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @empty
    <div class="text-center text-slate-500 py-8">
        Belum ada soal.
    </div>
    @endforelse

    {{ $soal->links() }}
</div>

@endsection