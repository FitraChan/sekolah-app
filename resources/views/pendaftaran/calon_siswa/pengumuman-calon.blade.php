@extends('layout.main')

@section('tittle')
Pengumuman
@endsection

@section('body')

<div class="max-w-5xl mx-auto p-6">

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            Pengumuman
        </h2>

        <p class="text-slate-500 mt-1">
            Informasi terbaru untuk calon siswa.
        </p>
    </div>

    @forelse ($pengumuman as $item)

        <div class="box p-5 mb-4">

            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                <div class="flex-1">

                    <div class="flex items-center gap-2 mb-2">

                        @if (is_null($item->id_gelombang))
                            <span class="px-2 py-1 text-xs rounded bg-primary text-white">
                                Semua Gelombang
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded bg-success text-white">
                                Gelombang {{ $item->id_gelombang }}
                            </span>
                        @endif

                    </div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        {{ $item->judul }}
                    </h3>

                    <div class="text-slate-500 text-sm mt-2">

                        <i data-lucide="calendar" class="w-4 h-4 inline-block mr-1"></i>

                        {{ $item->tanggal_kirim
                            ? \Carbon\Carbon::parse($item->tanggal_kirim)->format('d M Y H:i')
                            : '-' }}

                    </div>

                    <div class="mt-4 text-slate-600 leading-relaxed">

                        {{ \Illuminate\Support\Str::limit(
                            strip_tags($item->pesan),
                            200
                        ) }}

                    </div>

                </div>

                <div>
                    <a href="{{ route('pengumumanCalon.show', $item->id) }}"
                        class="btn btn-primary">

                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>

                        Baca

                    </a>
                </div>

            </div>

        </div>

    @empty

        <div class="box p-10 text-center">

            <div class="flex justify-center mb-4">
                <i data-lucide="megaphone"
                    class="w-12 h-12 text-slate-400">
                </i>
            </div>

            <h3 class="text-lg font-semibold text-slate-700">
                Belum Ada Pengumuman
            </h3>

            <p class="text-slate-500 mt-2">
                Pengumuman untuk gelombang Anda belum tersedia.
            </p>

        </div>

    @endforelse

    @if ($pengumuman->hasPages())
        <div class="mt-6">
            {{ $pengumuman->links() }}
        </div>
    @endif

</div>

@endsection