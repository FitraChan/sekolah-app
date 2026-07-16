@extends('layout.main')

@section('tittle')
Target Pendaftaran
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Target Pendaftaran</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">
        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-target">

            + Tambah Target

        </button>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Target Pendaftaran
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">
                        <div id="table-target"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('pendaftaran.target.add-target')
@include('pendaftaran.target.edit-target')

<script>
let table = new Tabulator("#table-target", {

    ajaxURL: "{{ route('target.data') }}",

    layout: "fitColumns",

    pagination: true,

    paginationSize: 10,

    placeholder: "Data target belum tersedia",

    columns: [

        {
            title: "No",
            formatter: "rownum",
            hozAlign: "center",
            width: 60
        },

        {
            title: "Tahun Ajaran",
            field: "thn_ajaran"
        },

        {
            title: "Jurusan",
            field: "nama_jurusan"
        },

        {
            title: "Target",
            field: "target",
            
            formatter: function(cell) {
                return formatAngka(cell.getValue());
            }
        },

      

        {
            title: "Persentase",
           
            formatter: function(cell) {

                let data = cell.getData();

                let target = parseFloat(data.target) || 0;
                let pencapaian = parseFloat(data.pencapaian) || 0;

                let persentase = target > 0
                    ? (pencapaian / target) * 100
                    : 0;

                let kelas = 'bg-danger';

                if (persentase >= 100) {
                    kelas = 'bg-success';
                } else if (persentase >= 75) {
                    kelas = 'bg-warning';
                }

                return `
                    <span class="px-2 py-1 rounded text-white ${kelas}">
                        ${persentase.toFixed(2)}%
                    </span>
                `;
            }
        },

        {
            title: "Sisa Target",
         
            formatter: function(cell) {

                let data = cell.getData();

                let target = parseFloat(data.target) || 0;
                let pencapaian = parseFloat(data.pencapaian) || 0;

                let sisa = target - pencapaian;

                return formatAngka(sisa > 0 ? sisa : 0);
            }
        },

        {
            title: "Action",
           
            headerSort: false,

            formatter: function(cell) {

                let data = cell.getData();

                let dataJson = JSON.stringify(data)
                    .replace(/'/g, "&#39;");

                return `
                    <div class="flex justify-center gap-2">

                        <button
                            type="button"
                            class="btn btn-primary"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-edit-target"
                            onclick='editTarget(${dataJson})'>

                            Edit

                        </button>

                        <button
                            type="button"
                            onclick="deleteTarget(${data.id})"
                            class="btn btn-danger">

                            Hapus

                        </button>

                    </div>
                `;
            }
        }
    ]
});


function formatAngka(value)
{
    return new Intl.NumberFormat('id-ID').format(
        parseFloat(value) || 0
    );
}


function editTarget(data)
{
    document.getElementById('edit_id').value =
        data.id ?? '';

    document.getElementById('edit_id_thn_ajaran').value =
        data.id_thn_ajaran ?? '';

    document.getElementById('edit_id_jurusan').value =
        data.id_jurusan ?? '';

    document.getElementById('edit_target').value =
        data.target ?? 0;

   
}


function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id !== '';

    let prefix = isEdit ? 'edit_' : 'add_';

    let method = isEdit ? 'put' : 'post';




    let url = isEdit
        ? "{{ url('target/update') }}/" + id
        : "{{ url('target/store') }}";

    let data = {
        id_thn_ajaran: document.getElementById(
            prefix + 'id_thn_ajaran'
        ).value,

        id_jurusan: document.getElementById(
            prefix + 'id_jurusan'
        ).value,

        target: document.getElementById(
            prefix + 'target'
        ).value,

        
    };

    fetch(url, {

        method: method,

        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        body: JSON.stringify(data)

    })
    .then(async response => {

        let result = await response.json();

        if (!response.ok) {
            throw result;
        }

        return result;
    })
    .then(result => {

        if (!result.success) {
            alert(result.message ?? 'Data gagal disimpan');
            return;
        }

        let modalSelector = isEdit
            ? "#modal-edit-target"
            : "#modal-add-target";

        const modal = tailwind.Modal.getOrCreateInstance(
            document.querySelector(modalSelector)
        );

        modal.hide();

        resetForm(isEdit);

        table.replaceData();

        alert(result.message ?? 'Data berhasil disimpan');
    })
    .catch(error => {

        console.error(error);

        if (error.errors) {

            let pesan = Object.values(error.errors)
                .flat()
                .join('\n');

            alert(pesan);

            return;
        }

        alert(
            error.message ??
            'Terjadi kesalahan saat menyimpan data'
        );
    });
}


function resetForm(isEdit)
{
    if (isEdit) {

        document.getElementById('form-edit-target').reset();

        document.getElementById('edit_id').value = '';

    } else {

        document.getElementById('form-add-target').reset();

    }
}


function deleteTarget(id)
{
    if (!confirm('Hapus data target ini?')) {
        return;
    }

    fetch("{{ url('target/delete') }}/" + id, {

        method: 'DELETE',

        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }

    })
    .then(async response => {

        let result = await response.json();

        if (!response.ok) {
            throw result;
        }

        return result;
    })
    .then(result => {

        if (!result.success) {
            alert(result.message ?? 'Data gagal dihapus');
            return;
        }

        table.replaceData();

        alert(result.message ?? 'Data berhasil dihapus');
    })
    .catch(error => {

        console.error(error);

        alert(
            error.message ??
            'Terjadi kesalahan saat menghapus data'
        );
    });
}

</script>

@endsection