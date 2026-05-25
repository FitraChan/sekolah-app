@extends('layout.main')

@section('tittle')
Set Kelas
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Set Kelas</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Data Set Kelas
                    </h2>

                </div>

                <div class="p-5" id="basic-table">

                    <div class="preview">

                        <div class="overflow-x-auto">

                            <div id="table-set-kelas"></div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL EDIT -->
<div id="modal-edit-kelas" class="modal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="font-medium text-base mr-auto">
                    Edit Kelas
                </h2>

            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                <input type="hidden" id="edit_id">

                <div class="col-span-12">

                    <label class="form-label">
                        Nama Siswa
                    </label>

                    <input type="text"
                           id="edit_nama"
                           class="form-control"
                           readonly>

                </div>

                <div class="col-span-12">

    <label class="form-label">
        Pilih Kelas
    </label>

    <select id="edit_id_kelas"
            class="form-select">

        @foreach($kelas as $item)

            @php

                if ($item->sisa < 0) {

                    $kuota = ' - Lebih ' . abs($item->sisa) . ' Siswa';

                } else {

                    $kuota = ' - Kuota ' . $item->sisa . ' Siswa';

                }

            @endphp

            <option value="{{ $item->idx }}">

                {{ $item->nama_kelas . $kuota }}

            </option>

        @endforeach

    </select>

</div>

            <div class="modal-footer">

               

                <button type="button"
                        onclick="saveKelas()"
                        class="btn btn-primary w-20">

                    Save

                </button>

            </div>

        </div>

    </div>

</div>

<script>
    let table = new Tabulator("#table-set-kelas", {

        ajaxURL: "{{ route('set-kelas.data') }}",

        layout: "fitColumns",

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                title: "No",
                formatter: "rownum",
                hozAlign: "center",
                width: 60,
            },

            {
                title: "Nama Siswa",
                field: "nama_lengkap"
            },

            {
                title: "Tahun Ajaran",
                field: "tahun_ajaran"
            },

            {
                title: "Jurusan",
                field: "nama_jurusan"
            },

            {
                title: "Kelas",
                field: "nama_kelas",

                formatter: function(cell) {

                    let value = cell.getValue();

                    return value ?? '-';

                }
            },

            {
                title: "Status Daftar",
                field: "status_daftar"
            },

            {
                title: "Action",
                hozAlign: "center",

                formatter: function(cell) {

                    let data = cell.getData();

                    return `
                        <button
                            class="btn btn-primary"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-edit-kelas"
                            onclick='editKelas(${JSON.stringify(data)})'>

                            Set Kelas

                        </button>
                    `;
                }
            }
        ],
    });

    function editKelas(data)
    {
        document.getElementById('edit_id').value = data.id;

        document.getElementById('edit_nama').value = data.nama_lengkap;

        document.getElementById('edit_id_kelas').value = data.id_kelas;
    }

    function saveKelas()
    {
        let id = document.getElementById('edit_id').value;

        fetch('/set-kelas/updateKelas/' + id, {

            method: 'POST',

            headers: {

                'Content-Type': 'application/json',

                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },

            body: JSON.stringify({

                id_kelas: document.getElementById('edit_id_kelas').value

            })

        })

        .then(res => res.json())

        .then(res => {

            const modal = tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-edit-kelas")
            );

            modal.hide();

            table.replaceData();

        });
    }
</script>

@endsection