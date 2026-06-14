@extends('layout.main')

@section('tittle')
Mapel
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Mata Pelajaran</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-mapel">

            + Tambah Mapel

        </button>

    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Mata Pelajaran
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                        <div class="flex flex-wrap gap-3 mb-4">

                            <select
                                id="filter_jurusan"
                                class="form-select w-56">

                                <option value="">
                                    Semua Jurusan
                                </option>

                                @foreach($jurusan as $item)

                                <option value="{{ $item->id }}">
                                    {{ $item->nama_jurusan }}
                                </option>

                                @endforeach

                            </select>

                            <select id="filter_kurikulum" class="form-select w-56">
                                <option value="">
                                    Semua Kurikulum
                                </option>

                                @foreach($kurikulum as $item)

                                <option value="{{ $item }}">
                                    {{ $item->kurikulum }}
                                </option>

                                @endforeach
                            </select>

                            <input
                                type="text"
                                id="filter_keyword"
                                class="form-control w-72"
                                placeholder="Cari Nama Mapel...">

                            <button
                                class="btn btn-primary"
                                onclick="loadDataMapel()">

                                Cari

                            </button>

                            <button
                                class="btn btn-secondary"
                                onclick="resetFilterMapel()">

                                Reset

                            </button>

                        </div>

                        <div id="table-mapel"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('akademik.mapel.add-mapel')
@include('akademik.mapel.edit-mapel')

<script>
    let table = new Tabulator("#table-mapel", {

        ajaxURL: "{{ route('mapel.data') }}",

        layout: "fitDataStretch",

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                    title: "No",
                    width: 70,
                    hozAlign: "center",
                    formatter: function(cell) {

                        let row = cell.getRow();
                        let table = row.getTable();

                        let page = table.getPage();
                        let size = table.getPageSize();

                        return ((page - 1) * size) + row.getPosition(true);
                    }
            },

            {
                title: "Nama Mapel",
                field: "nama_mapel",
                width: 250
            },

            {
                title: "Jurusan",
                field: "jurusan",
                width: 180
            },

            {
                title: "Kategori",
                field: "kategori",
                width: 220
            },

            {
                title: "Kurikulum",
                field: "kurikulum",
                width: 120
            },

            {
                title: "SMT1",
                field: "smt1",
                hozAlign: "center"
            },

            {
                title: "SMT2",
                field: "smt2",
                hozAlign: "center"
            },

            {
                title: "SMT3",
                field: "smt3",
                hozAlign: "center"
            },

            {
                title: "SMT4",
                field: "smt4",
                hozAlign: "center"
            },

            {
                title: "SMT5",
                field: "smt5",
                hozAlign: "center"
            },

            {
                title: "SMT6",
                field: "smt6",
                hozAlign: "center"
            },

            {
                title: "Ket",
                field: "ket",
                width: 80
            },

            {
                title: "Action",
                hozAlign: "center",
                width: 180,

                formatter: function(cell) {

                    let data = cell.getData();

                    return `
                    <button
                        class="btn btn-primary btn-sm"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-edit-mapel"
                        onclick='editMapel(${JSON.stringify(data)})'>

                        Edit

                    </button>

                    <button
                        onclick="deleteMapel(${data.id})"
                        class="btn btn-danger btn-sm">

                        Hapus

                    </button>
                `;
                }
            }
        ]
    });


    function editMapel(data) {
        document.getElementById('edit_id').value =
            data.id ?? '';

        document.getElementById('edit_nama_mapel').value =
            data.nama_mapel ?? '';

        document.getElementById('edit_id_jurusan').value =
            data.id_jurusan ?? '';

        document.getElementById('edit_id_kategori_mapel').value =
            data.id_kategori_mapel ?? '';

        document.getElementById('edit_kurikulum').value =
            data.kurikulum ?? '';

        document.getElementById('edit_smt1').value =
            data.smt1 ?? 0;

        document.getElementById('edit_smt2').value =
            data.smt2 ?? 0;

        document.getElementById('edit_smt3').value =
            data.smt3 ?? 0;

        document.getElementById('edit_smt4').value =
            data.smt4 ?? 0;

        document.getElementById('edit_smt5').value =
            data.smt5 ?? 0;

        document.getElementById('edit_smt6').value =
            data.smt6 ?? 0;

        document.getElementById('edit_ket').value =
            data.ket ?? 0;
    }


    function saveData() {
        let id = document.getElementById('edit_id').value;

        let isEdit = id != '';

        let prefix = isEdit ? 'edit_' : 'add_';

        let url = isEdit ?
            "{{ url('mapel/update') }}/" + id :
            "{{ url('mapel/store') }}";

        console.log(url);



        fetch(url, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                body: JSON.stringify({

                    nama_mapel: document.getElementById(prefix + 'nama_mapel').value,

                    id_jurusan: document.getElementById(prefix + 'id_jurusan').value,

                    id_kategori_mapel: document.getElementById(prefix + 'id_kategori_mapel').value,

                    kurikulum: document.getElementById(prefix + 'kurikulum').value,

                    smt1: document.getElementById(prefix + 'smt1').value,

                    smt2: document.getElementById(prefix + 'smt2').value,

                    smt3: document.getElementById(prefix + 'smt3').value,

                    smt4: document.getElementById(prefix + 'smt4').value,

                    smt5: document.getElementById(prefix + 'smt5').value,

                    smt6: document.getElementById(prefix + 'smt6').value,

                    ket: document.getElementById(prefix + 'ket').value,

                })

            })
            .then(res => res.json())
            .then(res => {

                const modal = isEdit ?
                    tailwind.Modal.getOrCreateInstance(
                        document.querySelector("#modal-edit-mapel")
                    ) :
                    tailwind.Modal.getOrCreateInstance(
                        document.querySelector("#modal-add-mapel")
                    );

                modal.hide();

                table.replaceData();

            });
    }


    function deleteMapel(id) {
        if (confirm('Hapus data ?')) {
            fetch("{{ url('mapel/delete') }}/" + id, {

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

    function loadDataMapel()
    {
        table.setData(
            "{{ route('mapel.data') }}",
            {
                id_jurusan : document.getElementById('filter_jurusan').value,
                kurikulum : document.getElementById('filter_kurikulum').value,
                keyword : document.getElementById('filter_keyword').value
            }
        );
    }
</script>

@endsection