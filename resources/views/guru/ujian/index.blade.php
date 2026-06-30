@extends('layout.main')

@section('tittle')
Jurusan
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Daftar Ujian</li>
</ol>
@endsection

@section('body')


<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-ujian">

            + Tambah Ujian

        </button>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Ujian
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                        <div id="table-ujian"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const tableQuiz = new Tabulator("#table-ujian", {

        data: @json($isi),

        layout: "fitDataStretch",

        pagination: true,

        paginationSize: 10,

        responsiveLayout: "collapse",

        placeholder: "Belum ada data.",

        columns: [

            {
                title: "No",
                formatter: "rownum",
                width: 60,
                hozAlign: "center"
            },

            {
                title: "Judul Ujian",
                field: "judul",
                width: 450

            },

            {
                title: "Jadwal Ujian",
                field: "tgl_quiz",
                width: 150
            },

            {
                title: "Kelas",
                field: "kelas",
                width: 100
            },

            {
                title: "Nama Mapel",
                field: "nama_mapel",
                width: 250
            },
            {
                title: "Aksi",
                width: 220,

                formatter: function(cell) {

                    let data = cell.getData();
                    return `                 
                      <a href="{{ url('pbm/dataDetQuiz') }}/${data.id}"
                        class="btn btn-sm btn-outline-primary mr-1">
                            <i data-lucide="pencil" class="w-4 h-4 mr-1"></i>
                            Edit
                        </a>
                    <a class="btn btn-sm btn-outline-danger"
                        href="hapusQuiz(${data.id})">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                        Hapus
                    </a>
                `;

                }

            }

        ]

    });
</script>
@endsection