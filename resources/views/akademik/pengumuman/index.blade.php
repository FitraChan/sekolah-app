@extends('layout.main')

@section('tittle')
Pengumuman
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Pengumuman</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">
        <button
            class="btn btn-primary"
            data-tw-toggle="modal"
            data-tw-target="#modal-add-pengumuman">

            + Tambah Pengumuman

        </button>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Daftar Pengumuman
                    </h2>

                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">
                        <div id="table-pengumuman"></div>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@include('akademik.pengumuman.add-pengumuman')
@include('akademik.pengumuman.edit-pengumuman')

<script>

let table = new Tabulator("#table-pengumuman", {

    ajaxURL: "{{ route('pengumuman.data') }}",

    layout: "fitColumns",

    pagination: true,

    paginationSize: 10,

    placeholder: "Belum ada data pengumuman",

    columns: [

        {
            title: "No",
            formatter: "rownum",
            hozAlign: "center",
            width: 60,
            headerSort: false
        },

        {
            title: "Judul",
            field: "judul",
            minWidth: 200
        },

        {
            title: "Kategori",
            field: "kategori",
            width: 140
        },

        {
            title: "Prioritas",
            field: "prioritas",
            width: 120,
            hozAlign: "center",

            formatter: function(cell) {

                let value = cell.getValue();

                if(value === 'darurat') {
                    return `
                        <span class="px-2 py-1 rounded bg-danger text-white">
                            Darurat
                        </span>
                    `;
                }

                if(value === 'penting') {
                    return `
                        <span class="px-2 py-1 rounded bg-warning text-white">
                            Penting
                        </span>
                    `;
                }

                return `
                    <span class="px-2 py-1 rounded bg-secondary text-white">
                        Normal
                    </span>
                `;
            }
        },

        {
            title: "Status",
            field: "status",
            width: 120,
            hozAlign: "center",

            formatter: function(cell) {

                let value = cell.getValue();

                if(value === 'published') {
                    return `
                        <span class="px-2 py-1 rounded bg-success text-white">
                            Published
                        </span>
                    `;
                }

                if(value === 'archived') {
                    return `
                        <span class="px-2 py-1 rounded bg-dark text-white">
                            Archived
                        </span>
                    `;
                }

                return `
                    <span class="px-2 py-1 rounded bg-secondary text-white">
                        Draft
                    </span>
                `;
            }
        },

        {
            title: "Tanggal Publish",
            field: "publish_at",
            width: 170
        },

        {
            title: "Pinned",
            field: "is_pinned",
            width: 90,
            hozAlign: "center",

            formatter: function(cell) {

                return cell.getValue() == 1
                    ? '<span class="text-success font-bold">Ya</span>'
                    : '<span class="text-slate-500">Tidak</span>';
            }
        },

        {
            title: "Action",
            hozAlign: "center",
            width: 180,
            headerSort: false,

            formatter: function(cell) {

                let data = cell.getData();

                return `
                    <a href="javascript:;"
                        class="btn btn-primary"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-edit-pengumuman"
                        onclick='editPengumuman(${JSON.stringify(data)})'>

                        Edit

                    </a>

                    <button
                        onclick="deletePengumuman(${data.id})"
                        class="btn btn-danger">

                        Hapus

                    </button>
                `;
            }
        }
    ]
});


function editPengumuman(data)
{
    document.getElementById('edit_id').value =
        data.id;

    document.getElementById('edit_kategori_id').value =
        data.kategori_id ?? '';

    document.getElementById('edit_judul').value =
        data.judul ?? '';

    document.getElementById('edit_isi').value =
        data.isi ?? '';

    document.getElementById('edit_prioritas').value =
        data.prioritas ?? 'normal';

    document.getElementById('edit_status').value =
        data.status ?? 'draft';

    document.getElementById('edit_publish_at').value =
        formatDateTimeLocal(data.publish_at);

    document.getElementById('edit_expired_at').value =
        formatDateTimeLocal(data.expired_at);

    document.getElementById('edit_is_pinned').checked =
        data.is_pinned == 1;
}


function formatDateTimeLocal(value)
{
    if(!value) {
        return '';
    }

    return value
        .replace(' ', 'T')
        .substring(0, 16);
}


function saveData()
{
    let id = document.getElementById('edit_id').value;

    let isEdit = id != '';

    let prefix = isEdit
        ? 'edit_'
        : 'add_';

    let url = isEdit
        ? "{{ url('pengumuman/update') }}/" + id
        : "{{ url('pengumuman/store') }}";


    let aksi = isEdit
        ? "PUT"
        : "POST";

    let formData = new FormData();

    formData.append(
        'kategori_id',
        document.getElementById(prefix + 'kategori_id').value
    );

    formData.append(
        'judul',
        document.getElementById(prefix + 'judul').value
    );

    formData.append(
        'isi',
        document.getElementById(prefix + 'isi').value
    );

    formData.append(
        'prioritas',
        document.getElementById(prefix + 'prioritas').value
    );

    formData.append(
        'status',
        document.getElementById(prefix + 'status').value
    );

    formData.append(
        'publish_at',
        document.getElementById(prefix + 'publish_at').value
    );

    formData.append(
        'expired_at',
        document.getElementById(prefix + 'expired_at').value
    );

    formData.append(
        'is_pinned',
        document.getElementById(prefix + 'is_pinned').checked
            ? 1
            : 0
    );

    formData.append(
    'target_type',
    document.getElementById(
        prefix + 'target_type'
    ).value
    );

    formData.append(
        'target_id',
        document.getElementById(
            prefix + 'target_id'
        ).value
    );

    

    let lampiran = document.getElementById(
        prefix + 'lampiran'
    ).files[0];

    if(lampiran) {
        formData.append('lampiran', lampiran);
    }

    fetch(url, {

        method: aksi,

        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },

        body: formData

    })
    .then(async res => {

        let response = await res.json();

        if(!res.ok) {
            throw response;
        }

        return response;
    })
    .then(res => {

        const modal = isEdit
            ? tailwind.Modal.getOrCreateInstance(
                document.querySelector(
                    "#modal-edit-pengumuman"
                )
              )
            : tailwind.Modal.getOrCreateInstance(
                document.querySelector(
                    "#modal-add-pengumuman"
                )
              );

        modal.hide();

        table.replaceData();

        alert('Data berhasil disimpan');
    })
    .catch(error => {

        if(error.errors) {

            let pesan = Object.values(error.errors)
                .flat()
                .join('\n');

            alert(pesan);

            return;
        }

        alert('Terjadi kesalahan saat menyimpan data');
    });
}

function ubahTargetPengumuman(mode)
{
    let targetType = document.getElementById(
        mode + '_target_type'
    ).value;

    let targetIdContainer = document.getElementById(
        mode + '_target_id_container'
    );

    

    let targetIdLabel = document.getElementById(
        mode + '_target_id_label'
    );

    targetIdContainer.classList.add('hidden');
  

    document.getElementById(
        mode + '_target_id'
    ).value = '';



    if(targetType === 'siswa') {
        targetIdContainer.classList.remove('hidden');
        targetIdLabel.innerText = 'ID Siswa';
    }

    if(targetType === 'kelas') {
        targetIdContainer.classList.remove('hidden');
        targetIdLabel.innerText = 'ID Kelas';
    }

    if(targetType === 'pendaftar') {
        targetIdContainer.classList.remove('hidden');
        targetIdLabel.innerText = 'ID Pendaftar';
    }

    if(targetType === 'gelombang_pendaftaran') {
        targetIdContainer.classList.remove('hidden');
        targetIdLabel.innerText = 'ID Gelombang';
    }

    if(targetType === 'jalur_pendaftaran') {
        targetIdContainer.classList.remove('hidden');
        targetIdLabel.innerText = 'ID Jalur Pendaftaran';
    }

    
}


function deletePengumuman(id)
{
    if(confirm('Hapus data pengumuman?'))
    {
        fetch("{{ url('pengumuman/delete') }}/" + id, {

            method: 'DELETE',

            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
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