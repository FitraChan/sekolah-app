@extends('layout.main')

@section('tittle')
Soal
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Daftar Soal</li>
</ol>
@endsection

@section('body')


<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <a href="{{ route('soalGuru.create') }}"
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-soal">

            + Tambah Soal

        </a>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Soal
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                        <div id="table-soal"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

    const tableSoal = new Tabulator("#table-soal", {

    ajaxURL: "{{ route('soalGuru.data') }}",
    ajaxConfig: "GET",

    layout: "fitDataStretch",

    pagination: true,
    paginationSize: 10,

    responsiveLayout: "collapse",

    placeholder: "Belum ada data.",

    columns: [

        {
            title: "No",
            width: 70,
            hozAlign: "center",
            formatter: function(cell) {

                let row = cell.getRow();
                let table = row.getTable();

                let page = table.getPage();
                let size = table.getPageSize();

                return ((page - 1) * size) + row.getPosition(true);
            }
        },

        {
            title: "Judul Soal",
            field: "judul_soal",
            widthGrow: 3
        },

        {
            title: "Jenis Soal",
            field: "jenis_soal",
            hozAlign: "center",
            width: 150
        },

        {
            title: "Soal",
            field: "soal",
            width: 220
        },

        {
            title: "Mata Pelajaran",
            field: "nama_mapel",
            width: 220
        },

        {
            title: "Semester",
            field: "semester",
            hozAlign: "center",
            width: 100
        },

        {
            title: "Diubah",
            field: "updated_at",
            width: 170
        },

        {
            title: "Aksi",
           
            width: 220,

            formatter: function (cell) {

                let data = cell.getData();

                return `
                    <a href="{{ url('soalGuru') }}/${data.id}"
                        class="btn btn-sm btn-outline-primary mr-1">
                        <i data-lucide="pencil" class="w-4 h-4"></i>
                        Edit
                    </a>

                    <button class="btn btn-sm btn-outline-danger"
                        onclick="hapusSoal(${data.id})">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Hapus
                    </button>
                `;
            }
        }

    ],

    renderComplete: function () {
        lucide.createIcons();
    }

});
</script>

@endsection