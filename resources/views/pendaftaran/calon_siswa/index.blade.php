@extends('layout.main')

@section('tittle')
Calon Siswa
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Calon Siswa</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">
    <!-- Bagian Tombol Aksi -->
    <div class="flex gap-2 mb-3">
         <a href="{{ route('calon-siswa.create') }}"
       class="btn btn-primary">

        + Tambah Calon Siswa

    </a>
    </div>

    <!-- Sistem Grid untuk membagi tabel menjadi sebelah-menyebelah -->
    <!-- md:grid-cols-3 artinya membagi halaman menjadi 3 kolom saat layar komputer/tablet -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Kolom Kiri: Tempat Table User (Kita beri porsi lebih besar, mengambil 2 kolom) -->
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto"> Daftar Calon Siswa</h2>

                </div>

                <div class="p-5" id="basic-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <div id="table-calon-siswa"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>





<script>

let table = new Tabulator("#table-calon-siswa", {

    ajaxURL: "{{ route('calon-siswa.data') }}",

    layout: "fitDataStretch",

    pagination: true,

    paginationSize: 10,

    columns: [

        {
            title: "No",
            formatter: "rownum",
            width: 70
        },

        {
            title: "Nama Lengkap",
            field: "nama_lengkap",
            width: 250
        },

        {
            title: "JK",
            field: "jk",
            width: 100
        },

        {
            title: "NISN",
            field: "nisn",
            width: 180
        },

        {
            title: "No HP",
            field: "no_hp",
            width: 180
        },

        {
            title: "Jurusan",
            field: "nama_jurusan",
            width: 220
        },

        {
            title: "Gelombang",
            field: "nama_gelombang",
            width: 220
        },

        {
            title: "Action",
            hozAlign: "center",

            formatter: function(cell)
            {
                let data = cell.getData();

                return `

                    <div class="flex gap-2">

                        <a href="/calon-siswa/edit/${data.id}"
                            target="_blank"
                            class="btn btn-primary btn-sm">

                                Edit

                            </a>

                        <button
                            class="btn btn-danger btn-sm"

                            onclick='deleteData(${data.id})'>

                            Hapus

                        </button>

                    </div>

                `;
            }
        }
    ]
});


function editData(data)
{
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama_lengkap').value =
        data.nama_lengkap;
    document.getElementById('edit_jk').value =
        data.jk;

    document.getElementById('edit_nisn').value =
        data.nisn;

    document.getElementById('edit_no_hp').value =
        data.no_hp;

    document.getElementById('edit_id_jurusan').value =
        data.id_jurusan;

    document.getElementById('edit_id_gelombang').value =
        data.id_gelombang;
}


function saveData(type)
{
    let prefix = type == 'add'
        ? 'add_'
        : 'edit_';

    let id = type == 'edit'
        ? document.getElementById('edit_id').value
        : '';

    let url = type == 'edit'
        ? '/calon-siswa/update/' + id
        : '/calon-siswa/store';

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            nama_lengkap:
                document.getElementById(prefix + 'nama_lengkap').value,

            jk:
                document.getElementById(prefix + 'jk').value,

            nisn:
                document.getElementById(prefix + 'nisn').value,

            no_hp:
                document.getElementById(prefix + 'no_hp').value,

            id_jurusan:
                document.getElementById(prefix + 'id_jurusan').value,

            id_gelombang:
                document.getElementById(prefix + 'id_gelombang').value,
        })

    })
    .then(res => res.json())
    .then(res => {

        table.replaceData();

    });
}


function deleteData(id)
{
    if(confirm('Yakin hapus data?'))
    {
        fetch('/calon-siswa/delete/' + id, {

            method: 'DELETE',

            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }

        })
        .then(res => res.json())
        .then(res => {

            table.replaceData();

        });
    }
}

</script>

@endsection
