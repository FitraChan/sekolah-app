@extends('layout.main')

@section('tittle')
Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Siswa</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    {{-- Tombol tambah --}}
    <div class="flex gap-2 mb-3">

        <a href="{{ route('siswa.create') }}"
            class="btn btn-primary">

            + Tambah Siswa

        </a>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 lg:col-span-12">

            <div class="intro-y box">

                <div
                    class="flex flex-col sm:flex-row items-center
                           p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Siswa
                    </h2>

                </div>

                <div class="p-5" id="basic-table">

                    <div class="preview">

                        <div class="overflow-x-auto">

                            <div class="flex flex-wrap gap-3 mb-4">

                                <div>
                                    <label class="form-label">Tahun Ajaran</label>

                                    <select id="filter-tahun" class="form-control">
                                        <option value="">Semua Tahun Ajaran</option>

                                        @foreach ($tahunAjaran as $tahun)
                                        <option value="{{ $tahun->id }}">
                                            {{ $tahun->thn_ajaran }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Jurusan</label>

                                    <select id="filter-jurusan" class="form-control">
                                        <option value="">Semua Jurusan</option>

                                        @foreach ($jurusan as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->nama_jurusan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label">Kelas</label>

                                    <select id="filter-kelas" class="form-control">
                                        <option value="">Semua Kelas</option>

                                        @foreach ($kelas as $item)
                                        <option value="{{ $item->idx }}">
                                            {{ $item->nama_kelas }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="flex flex-col">
                                    <label class="form-label mb-1">Nama / NIPD</label>
                                    <input
                                        type="text"
                                        id="search-siswa"
                                        class="form-control w-72"
                                        placeholder="Cari NIPD atau nama siswa...">
                                </div>


                                <div class="flex items-end">
                                    <button
                                        type="button"
                                        id="cari"
                                        class="btn btn-secondary w-full">
                                        Cari
                                    </button>
                                </div>

                            </div>



                            <div id="table-siswa"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    let table = new Tabulator("#table-siswa", {
        ajaxURL: "{{ route('siswa.data') }}",
        layout: "fitDataStretch",
        height: "500px",
        pagination: true,
        paginationSize: 10,

    

        columns: [{
                title: "No",
                width: 70,
                hozAlign: "center",
                headerSort: false,

                formatter: function(cell) {
                    let row = cell.getRow();
                    let table = row.getTable();
                    let page = table.getPage();
                    let size = table.getPageSize();

                    return ((page - 1) * size) + row.getPosition(true);
                }
            },
            {
                title: "NIPD",
                field: "nipd",
                width: 140,

                formatter: function(cell) {
                    return cell.getValue() ?? "-";
                }
            },
            {
                title: "Nama Lengkap",
                field: "nama_lengkap",
                width: 250
            },
            {
                title: "No HP",
                field: "no_hp",
                width: 180,

                formatter: function(cell) {
                    return cell.getValue() ?? "-";
                }
            },
            {
                title: "Jurusan",
                field: "nama_jurusan",
                width: 220,

                formatter: function(cell) {
                    return cell.getValue() ?? "-";
                }
            },
            {
                title: "Kelas",
                field: "nama_kelas",
                width: 180,

                formatter: function(cell) {
                    return cell.getValue() ?? "-";
                }
            },
            {
                title: "Tahun Ajaran",
                field: "tahun_ajaran",
                width: 160,

                formatter: function(cell) {
                    return cell.getValue() ?? "-";
                }
            },
            {
                title: "Action",
                width: 170,
                headerSort: false,

                formatter: function(cell) {
                    let data = cell.getData();

                    return `
                    <div class="flex gap-2 justify-center">
                        <a
                            href="{{ url('siswa/edit') }}/${data.id}"
                            target="_blank"
                            class="btn btn-primary btn-sm"
                        >
                            Edit
                        </a>

                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            onclick="deleteData(${data.id})"
                        >
                            Hapus
                        </button>
                    </div>
                `;
                }
            }
        ]
    });

    document
        .getElementById("search-siswa")
        .addEventListener("input", function() {

            const keyword = this.value.trim().toLowerCase();

            if (keyword === "") {
                table.clearFilter();
                return;
            }

            table.setFilter(function(data) {
                const nipd = String(data.nipd ?? "").toLowerCase();
                const nama = String(data.nama_lengkap ?? "").toLowerCase();

                return nipd.includes(keyword) || nama.includes(keyword);
            });
        });


  document.getElementById("cari").addEventListener("click", function () {
    const params = {
        tahun: document.getElementById("filter-tahun").value,
        jurusan: document.getElementById("filter-jurusan").value,
        kelas: document.getElementById("filter-kelas").value,
        search: document.getElementById("search-siswa").value.trim(),
    };

    console.log(params);

    table.setData("{{ route('siswa.data') }}", params);
});


    function deleteData(id) {

        if (!confirm('Yakin hapus data siswa?')) {
            return;
        }

        fetch("{{ url('siswa/delete') }}/" + id, {

                method: 'DELETE',

                headers: {

                    'X-CSRF-TOKEN': '{{ csrf_token() }}',

                    'Accept': 'application/json'

                }

            })
            .then(async response => {

                let result;

                try {

                    result = await response.json();

                } catch (error) {

                    throw new Error(
                        'Response server bukan JSON.'
                    );

                }

                if (!response.ok || result.success === false) {

                    throw new Error(
                        result.message ??
                        'Data siswa gagal dihapus.'
                    );

                }

                return result;

            })
            .then(result => {

                alert(
                    result.message ??
                    'Data siswa berhasil dihapus.'
                );

                table.replaceData();

            })
            .catch(error => {

                console.error(error);

                alert(error.message);

            });

    }
</script>

@endsection