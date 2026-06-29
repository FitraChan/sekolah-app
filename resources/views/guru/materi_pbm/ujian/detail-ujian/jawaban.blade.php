<div class="max-w-7xl mx-auto p-6">


    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="p-5">

                    <div class="overflow-x-auto">


                        <div class="flex items-center mb-3">

                            <div class="flex gap-2">
                                <button class="btn btn-outline-secondary" id="btn-copy">Copy</button>
                                <button class="btn btn-outline-primary" id="btn-csv">CSV</button>
                                <button class="btn btn-outline-success" id="btn-excel">Excel</button>
                                <button class="btn btn-outline-danger" id="btn-pdf">PDF</button>
                                <button class="btn btn-outline-warning" id="btn-print">Print</button>
                            </div>

                            <div class="w-80 ml-auto">
                                <input
                                    type="text"
                                    id="search-jawaban"
                                    class="form-control"
                                    placeholder="🔍 Cari siswa, NIS, skor...">
                            </div>

                        </div>
                        <div id="table-jawaban"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const dataJawaban = @json($jawabansiswa);
    const tableJawaban = new Tabulator("#table-jawaban", {

        data: dataJawaban,

        layout: "fitDataStretch",

        pagination: true,
        paginationSize: 10,

        placeholder: "Belum ada data.",

        columns: [

            {
                title: "No",
                formatter: "rownum",
                width: 60,
                hozAlign: "center"
            },

            {
                title: "NIPD",
                field: "nipd",
                width: 120
            },

            {
                title: "Nama Siswa",
                field: "nama_lengkap",
                width: 250
            },

            {
                title: "Mulai",
                field: "tgl_mulai_quiz",
                width: 200
            },

            {
                title: "Selesai",
                field: "tgl_selesai_quiz",
                width: 200
            },

            {
                title: "Skor",
                field: "total_skor",

                width: 100
            }

        ]

    });
</script>