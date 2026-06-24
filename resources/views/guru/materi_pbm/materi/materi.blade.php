<div class="max-w-7xl mx-auto p-6">

    <div class="intro-y col-span-11 alert alert-success alert-dismissible show flex items-center mb-6" role="alert">
        <span><i data-lucide="info" class="w-4 h-4 mr-2"></i></span>
        <span>
            <p> <?= 'Mata Pelajaran  ' . $master->mapel->nama_mapel; ?> </p>
        </span>
        <button type="button" class="btn-close text-white" data-tw-dismiss="alert" aria-label="Close">
    </div>

    <div class="intro-y box">

    <div class="p-5 border-b flex flex-col md:flex-row md:items-center gap-3">

    <h2 class="font-semibold text-lg">
        Kelas {{ $master->kelas->kelas }}
        {{ $master->kelas->nama_kelas }}
    </h2>

    <div class="md:ml-auto text-right">
        <button
            class="btn btn-primary shadow-md"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-materi">

            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Tambah Materi

        </button>
    </div>

</div>

        <div class="p-5">

            <div id="table-materi"></div>

        </div>

    </div>

</div>

<script>
    let table = new Tabulator("#table-materi", {

        data: @json($isi),

        layout: "fitDataStretch",

        pagination: true,

        paginationSize: 10,

        responsiveLayout: "collapse",

        columns: [

            {
                title: "Pertemuan",
                field: "idpertemuan",
                width: 110,
                hozAlign: "center"
            },

            {
                title: "Tanggal",
                field: "tgl",
                width: 120,
                hozAlign: "center"
            },

            {
                title: "Materi",
                field: "judul_materi",
                widthGrow: 2
            },

            {
                title: "Guru Pengganti",
                field: "nama_guru_pengganti",
                width: 180
            },

            {
                title: "Tugas",
                field: "judul_tugas",
                widthGrow: 2
            },

            {
                title: "Keterangan",
                field: "keterangan",
                widthGrow: 2
            },

            {
                title: "H",
                field: "H",
                width: 70,

            },

            {
                title: "I",
                field: "I",
                width: 70,

            },

            {
                title: "S",
                field: "S",
                width: 70,

            },

            {
                title: "A",
                field: "A",
                width: 70,

            },

            {
                title: "Action",
                width: 180,


                formatter: function(cell) {

                    let data = cell.getData();

                    return `
                    <a href="/absensi/${data.id}"
                        class="btn btn-primary btn-sm mr-1">
                        Absensi
                    </a>

                    <a href="/materi/${data.id}"
                        class="btn btn-success btn-sm">
                        Detail
                    </a>
                `;
                }
            }

        ]

    });
</script>