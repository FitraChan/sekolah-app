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

@include('keuangan.kat_item_bayar.modal')

<script>

let table = new Tabulator("#tableData", {

    ajaxURL: "{{ route('kat-item-bayar.data') }}",

    layout: "fitColumns",

    columns: [

        {
            title: "ID",
            field: "id",
            width: 80
        },

        {
            title: "Nama Kategori",
            field: "nama_kategori"
        },

        {
            title: "Aksi",
            formatter: function(cell){

                let row = cell.getData();

                return `
                    <a href="javascript:;"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-form"
                            onclick='editKatItemBayar(${JSON.stringify(row)})'
                            class="btn btn-primary">
                                Edit
                    </a>

                   <button
                        onclick="deleteKatItemBayar(${row.id})"
                        class="btn btn-danger">

                        Hapus

                    </button>
                `;
            }
        }
    ]
});

function saveData()
{
    let id = document.getElementById('id').value;

    let isEdit = id != '';

    let url = isEdit
    ? "{{ url('kat-item-bayar/update') }}/" + id
    : "{{ url('kat-item-bayar/store') }}";

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            nama_kategori: document.getElementById('nama_kategori').value

        })

    })
    .then(res => res.json())
    .then(res => {

        const modal = tailwind.Modal.getOrCreateInstance(
            document.querySelector("#modal-form")
        );

        modal.hide();

        table.replaceData();

    })
    .catch(err => {

        console.log(err);

    });
}

function editKatItemBayar(data)
{
    console.log(data);

    document.getElementById('id').value =
        data.id ?? '';

    document.getElementById('nama_kategori').value =
        data.nama_kategori ?? '';
}

function deleteKatItemBayar(id)
{
    if (confirm('Hapus data?')) {

        fetch("{{ url('kat-item-bayar/delete') }}/" + id, {

            method: 'DELETE',

            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }

        })
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




</script>

@endsection