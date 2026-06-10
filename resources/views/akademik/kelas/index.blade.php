@extends('layout.main')

@section('tittle')
Kelas
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Kelas</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">
        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-kelas">

            + Tambah Kelas

        </button>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Kelas
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">
                        <div id="table-kelas"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('akademik.kelas.add-kelas')
@include('akademik.kelas.edit-kelas')

<script>

let table = new Tabulator("#table-kelas", {

    ajaxURL: "{{ route('kelas.data') }}",

    layout: "fitColumns",

    pagination: true,

    paginationSize: 10,

    columns: [

        {
            title: "No",
            formatter: "rownum",
            hozAlign: "center",
            width: 60
        },

        {
            title: "Nama Kelas",
            field: "nama_kelas"
        },

        {
            title: "Jurusan",
            field: "jurusan"
        },

        {
            title: "Kelas",
            field: "kelas"
        },

        {
            title: "Alias",
            field: "alias"
        },

        {
            title: "Idx",
            field: "idx",
            width: 80
        },

        {
            title: "Action",
            hozAlign: "center",

            formatter: function(cell) {

                let data = cell.getData();

                return `
                    <a href="javascript:;"
                        class="btn btn-primary"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-edit-kelas"
                        onclick='editKelas(${JSON.stringify(data)})'>

                        Edit

                    </a>

                    <button
                        onclick="deleteKelas(${data.id})"
                        class="btn btn-danger">

                        Hapus

                    </button>
                `;
            }
        }
    ]
});


function editKelas(data)
{
    document.getElementById('edit_id').value = data.id;

    document.getElementById('edit_nama_kelas').value =
        data.nama_kelas ?? '';

    document.getElementById('edit_id_jurusan').value =
        data.id_jurusan ?? '';

    document.getElementById('edit_kelas').value =
        data.kelas ?? '';

    document.getElementById('edit_alias').value =
        data.alias ?? '';

    document.getElementById('edit_idx').value =
        data.idx ?? 0;
}


function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id != '';

    let prefix = isEdit ? 'edit_' : 'add_';

    let url = isEdit
        ? "{{ url('kelas/update') }}/" + id
        : "{{ url('kelas/store') }}";

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            nama_kelas: document.getElementById(prefix + 'nama_kelas').value,

            id_jurusan: document.getElementById(prefix + 'id_jurusan').value,

            kelas: document.getElementById(prefix + 'kelas').value,

            alias: document.getElementById(prefix + 'alias').value,

            idx: document.getElementById(prefix + 'idx').value,

        })

    })
    .then(res => res.json())
    .then(res => {

        const modal = isEdit
            ? tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-edit-kelas")
              )
            : tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-add-kelas")
              );

        modal.hide();

        table.replaceData();
    });
}


function deleteKelas(id)
{
    if(confirm('Hapus data ?'))
    {
        fetch("{{ url('kelas/delete') }}/" + id, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }

        })
        .then(res => res.json())
        .then(res => {

            table.replaceData();

            alert('Data berhasil dihapus');

        });
    }
}

</script>

@endsection