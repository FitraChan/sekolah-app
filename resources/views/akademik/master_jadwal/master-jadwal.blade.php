<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-jadwal">

            + Tambah Jadwal

        </button>


    <button
        class="btn btn-success"
        onclick="saveGuru()">
        <i data-lucide="credit-card" class="w-4 h-4 mr-1"></i>
        Simpan Perubahan Guru

    </button>



    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Jadwal
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">

                    <div class="flex flex-wrap gap-3 mb-4">

    <select id="filter_tahun" class="form-select w-56">
        <option value="">Semua Tahun Ajaran</option>

        @foreach($tahun as $item)
        <option value="{{ $item->id }}">
            {{ $item->thn_ajaran }}
        </option>
        @endforeach
    </select>

    <select id="filter_jurusan" class="form-select w-56">
        <option value="">Semua Jurusan</option>

        @foreach($jurusan as $item)
        <option value="{{ $item->id }}">
            {{ $item->nama_jurusan }}
        </option>
        @endforeach
    </select>

    <select id="filter_kelas" class="form-select w-56">
        <option value="">Semua Kelas</option>

        @foreach($kelas as $item)
        <option value="{{ $item->id }}">
            {{ $item->nama_kelas }}
        </option>
        @endforeach
    </select>

    <button
        class="btn btn-primary"
        onclick="loadData()">

        Cari

    </button>

    <button
        class="btn btn-secondary"
        onclick="resetFilter()">

        Reset

    </button>

</div>

                        <div id="table-jadwal"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

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