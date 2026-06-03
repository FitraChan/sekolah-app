@extends('layout.main')

@section('tittle')
Kategori Item Bayar
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Kategori Item Bayar</li>
</ol>
@endsection

@section('body')

<div class="box p-5">
    <div class="flex gap-2 mb-3">

        <button class="btn btn-primary" data-tw-toggle="modal" data-tw-target="#modal-form">
            Tambah Data
        </button>

    </div>

    <div id="tableData"></div>

</div>

@include('keuangan.item_bayar.modal')

<script>
    let table = new Tabulator("#tableData", {

        ajaxURL: "{{ route('item-bayar.data') }}",
        layout: "fitColumns",

        columns: [

            {
                title: "ID",
                field: "id",
                width: 80
            },

            {
                title: "Nama Item",
                field: "nama_item"
            },

            {
                title: "Kategori",
                field: "kategori"
            },

            {
                title: "Periode",
                field: "periode"
            },

            {
                title: "Default",
                field: "def_value",
                formatter: function(cell) {

                    let value = cell.getValue() || 0;

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);

                }
            },

            {
                title: "Keterangan",
                field: "keterangan"
            },

            {
                title: "Aksi",
                formatter: function(cell) {

                    let row = cell.getData();

                    return `
                <button
                    class="btn btn-primary"
                    data-tw-toggle="modal"
                    data-tw-target="#modal-form"
                    onclick='editItemBayar(${JSON.stringify(row)})'>
                    Edit
                </button>

                <button
                    class="btn btn-danger"
                    onclick='deleteItemBayar(${row.id})'>
                    Hapus
                </button>
            `;
                }
            }
        ]
    });

    function editItemBayar(data) {
        document.getElementById('id').value =
            data.id ?? '';

        document.getElementById('nama_item').value =
            data.nama_item ?? '';

        document.getElementById('id_kategori').value =
            data.id_kategori ?? '';

        document.getElementById('id_kat_periode').value =
            data.id_kat_periode ?? '';

        document.getElementById('def_value').value =
            data.def_value ?? '';

        document.getElementById('keterangan').value =
            data.keterangan ?? '';
    }

    function saveData() {
        let id = document.getElementById('id').value;

        let isEdit = id != '';

        let url = isEdit ?
            "{{ url('item-bayar/update') }}/" + id :
            "{{ url('item-bayar/store') }}";

        fetch(url, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                body: JSON.stringify({

                    nama_item: document.getElementById('nama_item').value,

                    id_kategori: document.getElementById('id_kategori').value,

                    id_kat_periode: document.getElementById('id_kat_periode').value,

                    def_value: document.getElementById('def_value').value,

                    keterangan: document.getElementById('keterangan').value

                })

            })
            .then(res => res.json())
            .then(res => {

                const modal = tailwind.Modal.getOrCreateInstance(
                    document.querySelector("#modal-form")
                );

                modal.hide();

                table.replaceData();

            });
    }



    function deleteItemBayar(id) {
        if (confirm('Hapus data?')) {

            fetch(
                    "{{ url('item-bayar/delete') }}/" + id, {

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

                });

        }
    }
</script>

@endsection