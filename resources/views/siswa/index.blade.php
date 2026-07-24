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

        columns: [

            {
                title: "No",
                width: 70,
                hozAlign: "center",
                headerSort: false,

                formatter: function(cell) {

                    let row = cell.getRow();

                    let table = row.getTable();

                    let page = table.getPage();

                    let size = table.getPageSize();

                    return ((page - 1) * size)
                        + row.getPosition(true);
                }
            },

            {
                title: "NIPD",
                field: "nipd",
                width: 140,

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Nama Lengkap",
                field: "nama_lengkap",
                width: 250
            },

            {
                title: "JK",
                field: "jk",
                width: 100,
                hozAlign: "center",

                formatter: function(cell) {

                    let value = cell.getValue();

                    if (value === 'L') {
                        return 'Laki-laki';
                    }

                    if (value === 'P') {
                        return 'Perempuan';
                    }

                    return '-';
                }
            },

            {
                title: "No HP",
                field: "no_hp",
                width: 180,

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Jurusan",
                field: "nama_jurusan",
                width: 220,

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Kelas",
                field: "nama_kelas",
                width: 180,

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Jenis Kelas",
                field: "jenis_kelas",
                width: 150,
                hozAlign: "center",

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Tahun Ajaran",
                field: "tahun_ajaran",
                width: 160,

                formatter: function(cell) {

                    return cell.getValue() ?? '-';

                }
            },

            {
                title: "Status",
                field: "is_aktif",
                width: 120,
                hozAlign: "center",

                formatter: function(cell) {

                    let value = cell.getValue();

                    if (
                        value === 1 ||
                        value === "1" ||
                        value === true
                    ) {
                        return `
                            <span
                                class="px-2 py-1 rounded
                                       bg-success/10
                                       text-success">

                                Aktif

                            </span>
                        `;
                    }

                    return `
                        <span
                            class="px-2 py-1 rounded
                                   bg-danger/10
                                   text-danger">

                            Tidak Aktif

                        </span>
                    `;
                }
            },

            {
                title: "Action",
                hozAlign: "center",
                width: 170,
                headerSort: false,

                formatter: function(cell) {

                    let data = cell.getData();

                    return `

                        <div class="flex gap-2 justify-center">

                            <a
                                href="{{ url('siswa/edit') }}/${data.id}"
                                target="_blank"
                                class="btn btn-primary btn-sm">

                                Edit

                            </a>

                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="deleteData(${data.id})">

                                Hapus

                            </button>

                        </div>

                    `;
                }
            }

        ]

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