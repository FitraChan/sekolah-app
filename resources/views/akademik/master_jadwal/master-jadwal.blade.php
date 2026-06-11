<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-jadwal">

            + Tambah Jadwal

        </button>


    <button
        class="btn btn-success"
        onclick="saveGuru()">
        <i data-lucide="credit-card" class="w-4 h-4 mr-1"></i>
        Simpan Perubahan Guru

    </button>



    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Jadwal
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                    <div class="flex flex-wrap gap-3 mb-4">

    <select id="filter_tahun" class="form-select w-56">
        <option value="">Semua Tahun Ajaran</option>

        @foreach($tahun as $item)
        <option value="{{ $item->id }}">
            {{ $item->thn_ajaran }}
        </option>
        @endforeach
    </select>

    <select id="filter_jurusan" class="form-select w-56">
        <option value="">Semua Jurusan</option>

        @foreach($jurusan as $item)
        <option value="{{ $item->id }}">
            {{ $item->nama_jurusan }}
        </option>
        @endforeach
    </select>

    <select id="filter_kelas" class="form-select w-56">
        <option value="">Semua Kelas</option>

        @foreach($kelas as $item)
        <option value="{{ $item->id }}">
            {{ $item->nama_kelas }}
        </option>
        @endforeach
    </select>

    <button
        class="btn btn-primary"
        onclick="loadData()">

        Cari

    </button>

    <button
        class="btn btn-secondary"
        onclick="resetFilter()">

        Reset

    </button>

</div>

                        <div id="table-jadwal"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>