@extends('layout.main')

@section('tittle')
Jurusan
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Jurusan</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-jurusan">

            + Tambah Jurusan

        </button>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Jurusan
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                        <div id="table-jurusan"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('akademik.jurusan.add-jurusan')
@include('akademik.jurusan.edit-jurusan')

<script>

let table = new Tabulator("#table-jurusan", {

    ajaxURL: "{{ route('jurusan.data') }}",

    layout: "fitColumns",

    pagination: true,

    paginationSize: 10,

    responsiveLayout: "collapse",

    columns: [

        {
            title: "No",
            formatter: "rownum",
            hozAlign: "center",
            width: 60
        },

        {
            title: "Nama Jurusan",
            field: "nama_jurusan"
        },

        {
            title: "Singkatan",
            field: "singkatan",
            hozAlign: "center",
            width: 120
        },

        {
            title: "Jumlah Siswa",
            field: "jumlah_siswa",
            hozAlign: "right",
            width: 150
        },

        {
            title: "Action",
            hozAlign: "center",
            width: 220,

            formatter: function(cell) {

                let data = cell.getData();

                return `
                    <button
                        class="btn btn-primary btn-sm mr-1"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-edit-jurusan"
                        onclick='editJurusan(${JSON.stringify(data)})'>

                        Edit

                    </button>

                    <button
                        class="btn btn-danger btn-sm"
                        onclick="deleteJurusan(${data.id})">

                        Hapus

                    </button>
                `;
            }
        }
    ]
});


function editJurusan(data)
{
    document.getElementById('edit_id').value =
        data.id ?? '';

    document.getElementById('edit_nama_jurusan').value =
        data.nama_jurusan ?? '';

    document.getElementById('edit_singkatan').value =
        data.singkatan ?? '';

    document.getElementById('edit_jumlah_siswa').value =
        data.jumlah_siswa ?? 0;
}


function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id !== '';

    let prefix = isEdit ? 'edit_' : 'add_';

    let url = isEdit
        ? "{{ url('jurusan/update') }}/" + id
        : "{{ url('jurusan/store') }}";

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'

        },

        body: JSON.stringify({

            nama_jurusan:
                document.getElementById(prefix + 'nama_jurusan').value,

            singkatan:
                document.getElementById(prefix + 'singkatan').value,

            jumlah_siswa:
                document.getElementById(prefix + 'jumlah_siswa').value

        })

    })
    .then(res => res.json())
    .then(res => {

        if(res.success)
        {
            const modal = isEdit
                ? tailwind.Modal.getOrCreateInstance(
                    document.querySelector("#modal-edit-jurusan")
                  )
                : tailwind.Modal.getOrCreateInstance(
                    document.querySelector("#modal-add-jurusan")
                  );

            modal.hide();

            table.replaceData();

            alert('Data berhasil disimpan');
        }
        else
        {
            alert('Gagal menyimpan data');
        }

    })
    .catch(err => {

        console.error(err);

        alert('Terjadi kesalahan');

    });
}


function deleteJurusan(id)
{
    if(confirm('Yakin ingin menghapus data ini ?'))
    {
        fetch("{{ url('jurusan/delete') }}/" + id, {

            method: 'DELETE',

            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}'

            }

        })
        .then(res => res.json())
        .then(res => {

            if(res.success)
            {
                table.replaceData();

                alert('Data berhasil dihapus');
            }

        })
        .catch(err => {

            console.error(err);

            alert('Gagal menghapus data');

        });
    }
}

</script>

@endsection