@extends('layout.main')

@section('tittle')
Pembayaran Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Pembayaran Siswa</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">




    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto"> Data Pembayaran Siswa</h2>

                    <button
                        class="btn btn-primary"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-form">

                        Tambah Data

                    </button>

                </div>

                <div class="p-5" id="basic-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <div id="tableBayar"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="intro-y col-span-12 lg:col-span-6">
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto"> History Pembayaran Siswa</h2>
                    <div class="flex gap-2">
                        <button
                            type="button"
                            class="btn btn-dark"
                            onclick="setDefaultDetail()">

                            Set Default

                        </button>


                        <button
                            type="button"
                            class="btn btn-primary"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-detail-form">

                            <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                            Tambah Detail

                        </button>
                    </div>
                </div>



                <div class="p-5" id="basic-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <div id="tableHistoryBayar"></div>

                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>


</div>

@include('keuangan.bayar.modal')
@include('keuangan.bayar.modal_detail')




<script>
    let tableSiswa = new Tabulator("#tableBayar", {

    ajaxURL: "{{ route('siswa.data') }}",

    layout: "fitDataStretch",

    height: "500px",

   
    columns: [

        {
            title: "No",
            formatter: "rownum",
            width: 60,
            hozAlign: "center"
        },

        {
            title: "NIPD",
            field: "nipd"
        },
        {
            title: "Nama Siswa",
            field: "nama_lengkap",
            width: 250
        },
        {
            title: "Kelas",
            field: "kelas.nama_kelas"
        },       
        {
            title: "Tahun",
            field: "tahun_ajaran.thn_ajaran"
        },       
    ]
});


    let currentTemplateId = 0;
    tableSiswa.on("rowClick", function(e, row) {
        showDetail(row.getData().id);
    });

   
   let tableDetail = new Tabulator("#tableDetailBayar", {

    layout: "fitDataStretch",

    height: "400px",

    columns: [

        {
            title: "No",
            formatter: "rownum",
            width: 60,
            hozAlign: "center"
        },

       
        {
            title: "Tahun",
            field: "tahun_ajaran"
        },

        {
            title: "Bulan",
            field: "bulan"
        },

        {
            title: "Tanggal Bayar",
            field: "tgl_bayar",
            width: 180
        },

        {
            title: "Total Bayar",
            field: "tot_bayar",
            hozAlign: "right",

            formatter: function(cell) {

                return new Intl.NumberFormat(
                    'id-ID',
                    {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }
                ).format(cell.getValue() || 0);

            }
        },

        {
            title: "Total Kewajiban",
            field: "tot_kwajiban",
            hozAlign: "right",

            formatter: function(cell) {

                return new Intl.NumberFormat(
                    'id-ID',
                    {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }
                ).format(cell.getValue() || 0);

            }
        },

        {
            title: "Keterangan",
            field: "keterangan",
            width: 250
        }
    ]
});



    function showDetail(idTemplate) {
        currentTemplateId = idTemplate;
        tableDetail.setData(
            "{{ url('template-bayar/detail') }}/" + idTemplate
        );

    }

    function saveData() {
        let id = document.getElementById('id').value;

        let isEdit = id != '';

        let url = isEdit ?
            "{{ url('template-bayar/update') }}/" + id :
            "{{ url('template-bayar/store') }}";

        fetch(url, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                body: JSON.stringify({

                    id_tahun: document.getElementById('id_tahun').value,

                    id_jurusan: document.getElementById('id_jurusan').value,

                    id_gelombang: document.getElementById('id_gelombang').value,

                    jns_kelas: document.getElementById('jns_kelas').value,

                    keterangan: document.getElementById('keterangan').value,

                    sts: document.getElementById('sts').value

                })

            })
            .then(res => res.json())
            .then(res => {

                const modal =
                    tailwind.Modal.getOrCreateInstance(
                        document.querySelector("#modal-form")
                    );

                modal.hide();

                table.replaceData();

            })
            .catch(err => {

                console.log(err);

            });
    }

    function editTemplateBayar(data) {
        document.getElementById('id').value =
            data.id ?? '';

        document.getElementById('id_tahun').value =
            data.id_tahun ?? '';

        document.getElementById('id_jurusan').value =
            data.id_jurusan ?? '';

        document.getElementById('id_gelombang').value =
            data.id_gelombang ?? '';

        document.getElementById('jns_kelas').value =
            data.jns_kelas ?? '';

        document.getElementById('keterangan').value =
            data.keterangan ?? '';

        document.getElementById('sts').value =
            data.sts ?? 1;
    }

    function deleteTemplateBayar(id) {
        if (confirm('Hapus data?')) {

            fetch(
                    "{{ url('template-bayar/delete') }}/" + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }
                )
                .then(res => res.json())
                .then(res => {
                    table.replaceData();
                    alert('Berhasil dihapus');
                })
                .catch(err => {
                    console.log(err);
                    alert('Gagal menghapus data');
                });
        }
    }


    function saveDetail() {

        let idDetail = document.getElementById('id_detail').value;

        let url = idDetail ?
            "{{ url('template-bayar-detail/updateDetail') }}/" + idDetail :
            "{{ route('template-bayar-detail.storeDetail') }}";

        fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    id_template: currentTemplateId,
                    id_item: document.getElementById('id_item').value,
                    jml_bayar: document.getElementById('jml_bayar').value,
                    ket_bayar: document.getElementById('ket_bayar').value
                })
            })
            .then(response => response.json())
            .then(response => {

                const modal = tailwind.Modal.getOrCreateInstance(
                    document.querySelector("#modal-detail-form")
                );

                modal.hide();

                // reset form
                document.getElementById('id_detail').value = '';
                document.getElementById('id_item').value = '';
                document.getElementById('jml_bayar').value = '';
                document.getElementById('ket_bayar').value = '';

                tableDetail.setData(
                    "{{ url('template-bayar/detail') }}/" + currentTemplateId
                );

                toastr.success(response.msg);

            })
            .catch(error => {
                console.error(error);
                toastr.error('Terjadi kesalahan');
            });
    }

    function editDetail(row) {

        document.getElementById('id_detail').value = row.id;
        document.getElementById('id_item').value = row.id_item;
        document.getElementById('jml_bayar').value = row.jml_bayar;
        document.getElementById('ket_bayar').value = row.ket_bayar ?? '';

        const modal = tailwind.Modal.getOrCreateInstance(
            document.querySelector("#modal-detail-form")
        );

        modal.show();
    }

    function deleteDetail(id) {

        if (!confirm('Yakin ingin menghapus data ini?')) {
            return;
        }

        fetch("{{ url('template-bayar-detail/deleteDetail') }}/" + id, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(response => {

                tableDetail.setData(
                    "{{ url('template-bayar/detail') }}/" + currentTemplateId
                );


            })
            .catch(error => {

                console.error(error);


            });
    }
</script>

@endsection