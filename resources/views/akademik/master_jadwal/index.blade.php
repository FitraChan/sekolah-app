@extends('layout.main')

@section('tittle')
Master Jadwal
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Master Jadwal</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">
    <ul class="nav nav-boxed-tabs" role="tablist">
        <li class="nav-item flex-1" role="presentation">
            <button
                class="nav-link w-full py-2 active"
                data-tw-toggle="pill"
                data-tw-target="#master-jadwal"
                type="button"
                role="tab"
                aria-controls="master-jadwal"
                aria-selected="true">

                Master Jadwal

            </button>
        </li>

        <li class="nav-item flex-1" role="presentation">
            <button
                class="nav-link w-full py-2"
                data-tw-toggle="pill"
                data-tw-target="#detail-jadwal"
                type="button"
                role="tab"
                aria-controls="detail-jadwal"
                aria-selected="false">

                Detail Jadwal

            </button>
        </li>

    </ul>

    <div class="tab-content mt-5">

        <div
            id="master-jadwal"
            class="tab-pane leading-relaxed active"
            role="tabpanel">

            @include('akademik.master_jadwal.master-jadwal')

        </div>

        <div
            id="detail-jadwal"
            class="tab-pane leading-relaxed"
            role="tabpanel">

            @include('akademik.master_jadwal.detail-jadwal')

        </div>

    </div>

</div>

@include('akademik.master_jadwal.tab.add-master-jadwal')
@include('akademik.master_jadwal.tab.edit-master-jadwal')

<script>

const guruOptions = @json(
    $guru->pluck('nama_gtk', 'id')
);

let changedRows = [];

let table = new Tabulator("#table-jadwal", {

    ajaxURL: "{{ route('master-jadwal.data') }}",

    layout: "fitDataStretch",
        ajaxParams: {},

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
            title: "Tahun Ajaran",
            field: "tahun_ajaran",
            width: 180
        },

        {
            title: "Semester",
            field: "semester",
            width: 100,
            hozAlign: "center"
        },

        {
            title: "Kelas",
            field: "nama_kelas",
            width: 180
        },

        {
            title: "Mapel",
            field: "nama_mapel",
            width: 250
        },

         {
            title: "Guru",
    field: "id_gtk",

    editor: "list",

    editorParams: {
        values: guruOptions,
        autocomplete: true,
        listOnEmpty: true,
        freetext: false
    },

    formatter: function(cell){
        return guruOptions[cell.getValue()] ?? "-";
    }
        },

        

        {
            title: "Jml Jam",
            field: "jml_jam",
            width: 100,
            hozAlign: "center"
        },

        {
            title: "Angkatan",
            field: "angkatan",
            width: 120,
            hozAlign: "center"
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
                        data-tw-target="#modal-edit-jadwal"
                        onclick='editJadwal(${JSON.stringify(data)})'>

                        Edit

                    </button>

                    <button
                        onclick="deleteJadwal(${data.id})"
                        class="btn btn-danger btn-sm">

                        Hapus

                    </button>
                `;
            }
        }
    ]
});

function loadData()
{
    table.setData(
        "{{ route('master-jadwal.data') }}",
        {
            id_tahun: document.getElementById('filter_tahun').value,
            id_jurusan: document.getElementById('filter_jurusan').value,
            id_kelas: document.getElementById('filter_kelas').value
        }
    );
}

function resetFilter()
{
    document.getElementById('filter_tahun').value = '';
    document.getElementById('filter_jurusan').value = '';
    document.getElementById('filter_kelas').value = '';

    loadData();
}

table.on("cellEdited", function(cell){

    let data = cell.getRow().getData();

    let idx = changedRows.findIndex(
        x => x.id == data.id
    );

    if(idx === -1){
        changedRows.push({...data});
    }else{
        changedRows[idx] = {...data};
    }

    //console.log(changedRows);
});


function editJadwal(data)
{
    document.getElementById('edit_id').value =
        data.id ?? '';

    document.getElementById('edit_id_tahun').value =
        data.id_tahun ?? '';

    document.getElementById('edit_semester').value =
        data.semester ?? '';

    document.getElementById('edit_id_kelas').value =
        data.id_kelas ?? '';

   document.getElementById('edit_id_mapel')
    .tomselect
    .setValue(data.id_mapel ?? '');

    document.getElementById('edit_id_gtk').value =
        data.id_gtk ?? '';

    document.getElementById('edit_jml_jam').value =
        data.jml_jam ?? '';

    
}


function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id != '';

    let prefix = isEdit ? 'edit_' : 'add_';

    let url = isEdit
        ? "{{ url('master-jadwal/update') }}/" + id
        : "{{ url('master-jadwal/store') }}";

    fetch(url, {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify({

            id_tahun:
                document.getElementById(prefix + 'id_tahun').value ?? '',

            semester:
                document.getElementById(prefix + 'semester').value ?? '',

            id_kelas:
                document.getElementById(prefix + 'id_kelas').value ?? '',

            id_mapel:
                document.getElementById(prefix + 'id_mapel').value ?? '',

            id_gtk:
                document.getElementById(prefix + 'id_gtk').value ?? '',

            jml_jam:
                document.getElementById(prefix + 'jml_jam').value ?? '',

           

        })

    })
    .then(res => res.json())
    .then(res => {

        const modal = isEdit
            ? tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-edit-jadwal")
              )
            : tailwind.Modal.getOrCreateInstance(
                document.querySelector("#modal-add-jadwal")
              );

        modal.hide();

        table.replaceData();

    });
}


function deleteJadwal(id)
{
    if(confirm('Hapus data ?'))
    {
        fetch("{{ url('master-jadwal/delete') }}/" + id, {

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

function saveGuru()
{
    if(changedRows.length === 0)
    {
        alert('Tidak ada perubahan');

        return;
    }

    fetch("{{ route('master-jadwal.update-guru') }}", {

        method: "POST",

        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },

        body: JSON.stringify({
            data: changedRows
        })

    })
    .then(res => res.json())
    .then(res => {

        alert(res.msg);

        changedRows = [];

        table.replaceData();
    });
}

</script>

@endsection