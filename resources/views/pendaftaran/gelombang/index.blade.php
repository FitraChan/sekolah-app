@extends('layout.main')

@section('tittle')
Gelombang
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Gelombang</li>
</ol>
@endsection

@section('body')


<div class="max-w-7xl mx-auto p-6">
    <!-- Bagian Tombol Aksi -->
    <div class="flex gap-2 mb-3">
        <button class="btn btn-primary" data-tw-toggle="modal" data-tw-target="#modal-add-gelombang">
            + Tambah Gelombang
        </button>
    </div>

    <!-- Sistem Grid untuk membagi tabel menjadi sebelah-menyebelah -->
    <!-- md:grid-cols-3 artinya membagi halaman menjadi 3 kolom saat layar komputer/tablet -->
    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- Kolom Kiri: Tempat Table User (Kita beri porsi lebih besar, mengambil 2 kolom) -->
        <div class="intro-y col-span-12 lg:col-span-12">
            <div class="intro-y box">
                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto"> Daftar Gelombang</h2>

                </div>

                <div class="p-5" id="basic-table">
                    <div class="preview">
                        <div class="overflow-x-auto">
                            <div id="table-gelombang"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

<!-- MODAL -->

@include('pendaftaran.gelombang.add-gelombang')
@include('pendaftaran.gelombang.edit-gelombang')

<script>
    let table = new Tabulator("#table-gelombang", {

        ajaxURL: "{{ route('gelombang.data') }}",

        layout: "fitColumns",

        pagination: true,

        paginationSize: 10,

        columns: [

          {
            title: "No",
            formatter: "rownum",
            hozAlign: "center",
            width: 60, // Sedikit diperkecil agar hemat ruang di kolom kanan
        },

            {
                title: "Tahun",
                field: "id_tahun"
            },

            {
                title: "Nama Gelombang",
                field: "nama_gelombang"
            },

            {
                title: "Awal",
                field: "awal",

                formatter: function(cell) {
                    let value = cell.getValue();

                    if (!value) return '';

                    let date = new Date(value);

                    let day = String(date.getDate()).padStart(2, '0');

                    let month = String(date.getMonth() + 1).padStart(2, '0');

                    let year = date.getFullYear();

                    return `${day}-${month}-${year}`;
                }
            },

            {
                title: "Akhir",
                field: "akhir",

                formatter: function(cell) {
                    let value = cell.getValue();

                    if (!value) return '';

                    let date = new Date(value);

                    let day = String(date.getDate()).padStart(2, '0');

                    let month = String(date.getMonth() + 1).padStart(2, '0');

                    let year = date.getFullYear();

                    return `${day}-${month}-${year}`;
                }
            },
            {
                title: "Status",
                field: "is_current",

                formatter: function(cell) {
                    let value = cell.getValue();

                    if (value == 1) {
                        return `
                <span class="text-success font-medium">
                    Aktif
                </span>
            `;
                    }

                    return `
            <span class="text-danger font-medium">
                Tidak
            </span>
        `;
                }
            },

            {
                title: "Idx",
                field: "idx"
            },

            {
                title: "Action",
                hozAlign: "center",
                formatter: function(cell) {
                    let data = cell.getData();
                    return `
                            <a href="javascript:;"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-edit-gelombang"
                            onclick='editGelombang(${JSON.stringify(data)})'
                            class="btn btn-primary">
                                Edit
                            </a>     
                            
                             <button
                            onclick="deleteGelombang(${data.id})"
                            class="btn btn-danger">

                            Hapus

                        </button>
                        `;
                }

            }
        ],
    });

    function openTambah() {
        document.getElementById('modal-add-gelombang').classList.remove('hidden');

        document.getElementById('add_id').value = '';

        document.getElementById('add_id_tahun').value = '';

        document.getElementById('add_nama_gelombang').value = '';

        document.getElementById('add_awal').value = '';

        document.getElementById('add_akhir').value = '';

        document.getElementById('add_is_current').value = 1;

        document.getElementById('add_idx').value = 0;
        
    }

    function closeModal() {
        document.getElementById('modal-add-gelombang').classList.add('hidden');
        document.getElementById('modal-edit-gelombang').classList.add('hidden');
    }

    function editGelombang(
        data
    ) {

        console.log(data);

        document.getElementById('modal-edit-gelombang').classList.remove('hidden');

        document.getElementById('edit_id').value = data.id;

        document.getElementById('edit_id_tahun').value = data.id_tahun;

        document.getElementById('edit_nama_gelombang').value = data.nama_gelombang;

        document.getElementById('edit_awal').value =
            data.awal ? data.awal.split('T')[0] : '';

        document.getElementById('edit_akhir').value =
            data.akhir ? data.akhir.split('T')[0] : '';

        document.getElementById('edit_is_current').value = data.is_current;

        document.getElementById('edit_idx').value = data.idx;
    }

    function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id != '';

    let prefix = isEdit ? 'edit_' : 'add_';

    let url = isEdit
        ? '/gelombang/update/' + id
        : '/gelombang/store';

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            id_tahun: document.getElementById(prefix + 'id_tahun').value,

            nama_gelombang: document.getElementById(prefix + 'nama_gelombang').value,

            awal: document.getElementById(prefix + 'awal').value,

            akhir: document.getElementById(prefix + 'akhir').value,

            is_current: document.getElementById(prefix + 'is_current').value,

            idx: document.getElementById(prefix + 'idx').value,
        })

    })
    .then(res => res.json())
    .then(res => {

        const modal = isEdit
            ? tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-edit-gelombang")
              )
            : tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-add-gelombang")
              );

        modal.hide();

        table.replaceData();

    });
}

    function deleteGelombang(id) {
        if (confirm('Hapus data?')) {
            fetch('/gelombang/delete/' + id, {

                    method: 'DELETE',

                    headers: {

                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }

                })
                .then(res => res.json())
                .then(res => {

                    table.replaceData();

                    alert('Berhasil dihapus');
                });
        }
    }
</script>

@endsection