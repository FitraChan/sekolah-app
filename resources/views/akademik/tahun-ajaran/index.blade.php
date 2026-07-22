@extends('layout.main')

@section('tittle')
Tahun Ajaran
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Tahun Ajaran</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <div class="grid grid-cols-12 gap-6 mt-5">
        <!-- tahun ajara -->
        <div class="intro-y col-span-12 lg:col-span-6">



            <div
                id="alert-message"
                class="hidden alert mt-5"></div>

            <!-- <div class="grid grid-cols-12 gap-6 mt-5"> -->

            <div class="intro-y box">

           <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-5 border-b border-slate-200/60"> <h2 class="font-medium text-base mr-auto"> Daftar Tahun Ajaran </h2> <button type="button" class="btn btn-primary" data-tw-toggle="modal" data-tw-target="#modal-add-tahun-ajaran"> + Tambah Tahun Ajaran </button> </div>

                <div class="p-5">

                    <div class="overflow-x-auto">
                        <div id="table-tahun-ajaran"></div>
                    </div>

                </div>

            </div>



            <!-- </div> -->
        </div>


        <div class="intro-y col-span-12 lg:col-span-6">

            <div class="intro-y box">

                <div
                    class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">
                        Setting
                    </h2>
                </div>

                <div class="p-5">

                    <div class="overflow-x-auto">
                        <div id="table-konfig"></div>
                    </div>

                </div>

            </div>

        </div>


    </div>
</div>

@include('akademik.tahun-ajaran.add')
@include('akademik.tahun-ajaran.edit')

@include('konfig.edit-konfig')


<script>
    const csrfToken = '{{ csrf_token() }}';

    let table = new Tabulator("#table-tahun-ajaran", {
        ajaxURL: "{{ route('tahunAjaran.data') }}",

        layout: "fitColumns",

        pagination: true,

        paginationSize: 10,

        placeholder: "Belum ada data tahun ajaran.",

        columns: [{
                title: "No",
                formatter: "rownum",
                hozAlign: "center",
                width: 70
            },
            {
                title: "Tahun Ajaran",
                field: "thn_ajaran"
            },
            {
                title: "Status",
                field: "isaktiv",
               
                formatter: function(cell) {
                    const aktif = Number(cell.getValue()) === 1;

                    if (aktif) {
                        return `
                            <span class="px-2 py-1 rounded bg-success text-white">
                                Aktif
                            </span>
                        `;
                    }

                    return `
                        <span class="px-2 py-1 rounded bg-slate-500 text-white">
                            Tidak Aktif
                        </span>
                    `;
                }
            },
            {
                title: "Action",
               
                width: 220,
                headerSort: false,

                formatter: function(cell) {
                    const data = cell.getData();

                    const encodedData = encodeURIComponent(
                        JSON.stringify(data)
                    );

                    return `
                        <button
                            type="button"
                            class="btn btn-primary mr-1"
                            data-tw-toggle="modal"
                            data-tw-target="#modal-edit-tahun-ajaran"
                            onclick="editTahunAjaran('${encodedData}')"
                        >
                            Edit
                        </button>

                        <button
                            type="button"
                            onclick="deleteTahunAjaran(${data.id})"
                            class="btn btn-danger"
                        >
                            Hapus
                        </button>
                    `;
                }
            }
        ]
    });


    function editTahunAjaran(encodedData) {
        const data = JSON.parse(
            decodeURIComponent(encodedData)
        );

        document.getElementById('edit_id_thn').value =
            data.id ?? '';

        document.getElementById('edit_thn_ajaran').value =
            data.thn_ajaran ?? '';

        document.getElementById('edit_isaktiv').value =
            Number(data.isaktiv) === 1 ? '1' : '0';

        clearValidationErrors();
    }


    async function saveData(type) {
        const isEdit = type === 'edit';

        const prefix = isEdit ? 'edit_' : 'add_';

        const id = isEdit ?
            document.getElementById('edit_id_thn').value :
            null;

        const url = isEdit ?
            "{{ url('tahunAjaran/update') }}/" + id :
            "{{ route('tahunAjaran.store') }}";

        const button = document.getElementById(
            isEdit ?
            'btn-save-edit' :
            'btn-save-add'
        );

        clearValidationErrors();

        button.disabled = true;
        button.innerHTML = 'Menyimpan...';

        try {
            const response = await fetch(url, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },

                body: JSON.stringify({
                    thn_ajaran: document
                        .getElementById(prefix + 'thn_ajaran')
                        .value
                        .trim(),

                    isaktiv: document
                        .getElementById(prefix + 'isaktiv')
                        .value
                })
            });

            const result = await response.json();

            if (!response.ok) {
                if (response.status === 422) {
                    showValidationErrors(result.errors, prefix);
                }

                throw new Error(
                    result.message ?? 'Data gagal disimpan.'
                );
            }

            const modalSelector = isEdit ?
                "#modal-edit-tahun-ajaran" :
                "#modal-add-tahun-ajaran";

            const modal = tailwind.Modal.getOrCreateInstance(
                document.querySelector(modalSelector)
            );

            modal.hide();

            if (!isEdit) {
                resetAddForm();
            }

            await table.replaceData();
            await tableKonfig.replaceData();


            showAlert(
                result.message ?? 'Data berhasil disimpan.',
                'success'
            );

        } catch (error) {
            showAlert(error.message, 'danger');

        } finally {
            button.disabled = false;
            button.innerHTML = 'Simpan';
        }
    }


    async function deleteTahunAjaran(id) {
        const confirmation = confirm(
            'Hapus tahun ajaran ini?'
        );

        if (!confirmation) {
            return;
        }

        try {
            const response = await fetch(
                "{{ url('tahunAjaran/delete') }}/" + id, {
                    method: 'DELETE',

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                }
            );

            const result = await response.json();

            if (!response.ok) {
                throw new Error(
                    result.message ?? 'Data gagal dihapus.'
                );
            }

            await table.replaceData();

            showAlert(
                result.message ?? 'Data berhasil dihapus.',
                'success'
            );

        } catch (error) {
            showAlert(error.message, 'danger');
        }
    }


    function showValidationErrors(errors, prefix) {
        Object.keys(errors ?? {}).forEach(function(field) {
            const errorElement = document.getElementById(
                prefix + field + '_error'
            );

            if (errorElement) {
                errorElement.innerText = errors[field][0];
                errorElement.classList.remove('hidden');
            }
        });
    }


    function clearValidationErrors() {
        document
            .querySelectorAll('.validation-error')
            .forEach(function(element) {
                element.innerText = '';
                element.classList.add('hidden');
            });
    }


    function resetAddForm() {
        document.getElementById('add_thn_ajaran').value = '';
        document.getElementById('add_isaktiv').value = '0';

        clearValidationErrors();
    }


    function showAlert(message, type = 'success') {
        const alertElement = document.getElementById(
            'alert-message'
        );

        alertElement.className =
            type === 'success' ?
            'alert alert-success mt-5' :
            'alert alert-danger mt-5';

        alertElement.innerText = message;
        alertElement.classList.remove('hidden');

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });

        setTimeout(function() {
            alertElement.classList.add('hidden');
        }, 4000);
    }


    // ============== config


    let tableKonfig = new Tabulator("#table-konfig", {

        ajaxURL: "{{ route('konfig.data') }}",

        layout: "fitDataStretch",

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
                title: "ID Tahun",
                field: "id_tahun",

            },
           

            {
                 title: "Semester",
                field: "smt",
                hozAlign: "center",
                formatter: function(cell) {
                    return cell.getValue() == 1 ? "Ganjil" : "Genap";
                }
            },

            {
                title: "ID Tahun PPDB",
                field: "id_thn_ppdb",

            },

            {
                title: "Action",
              //  hozAlign: "center",
                width: 220,

                formatter: function(cell) {

                    let data = cell.getData();

                    return `
                    <button
                        class="btn btn-primary btn-sm mr-1"
                        data-tw-toggle="modal"
                        data-tw-target="#modal-edit-konfig"
                        onclick='editKonfig(${JSON.stringify(data)})'>

                        Edit

                    </button>

                   
                `;
                }
            }
        ]
    });


    function editKonfig(data) {
        document.getElementById('edit_id').value =
            data.id ?? '';

        document.getElementById('edit_id_tahun').value =
            data.id_tahun ?? '';

        // document.getElementById('edit_id_gelombang').value =
        //     data.id_gelombang ?? '';

        document.getElementById('edit_smt').value =
            data.smt ?? 1;

        document.getElementById('edit_id_thn_ppdb').value =
            data.id_thn_ppdb ?? '';
    }


    function saveDataKonfig() {

    
        let id = document.getElementById('edit_id').value;

        let isEdit = id !== '';

        let prefix = isEdit ? 'edit_' : 'add_';

        let url = isEdit ?
            "{{ url('konfig/update') }}/" + id :
            "{{ url('konfig/store') }}";

        fetch(url, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'

                },

                body: JSON.stringify({

                    id_tahun: document.getElementById(prefix + 'id_tahun').value,

                    smt: document.getElementById(prefix + 'smt').value,

                    id_thn_ppdb: document.getElementById(prefix + 'id_thn_ppdb').value

                })

            })
            .then(res => res.json())
            .then(res => {

                if (res.success) {
                    const modal = isEdit ?
                        tailwind.Modal.getOrCreateInstance(
                            document.querySelector("#modal-edit-konfig")
                        ) :
                        tailwind.Modal.getOrCreateInstance(
                            document.querySelector("#modal-add-konfig")
                        );

                    modal.hide();

                    tableKonfig.replaceData();

                    alert('Data berhasil disimpan');
                } else {
                    alert('Gagal menyimpan data');
                }

            })
            .catch(err => {

                console.error(err);

                alert('Terjadi kesalahan');

            });
    }


    function deleteKonfig(id) {
        if (confirm('Yakin ingin menghapus data ini ?')) {
            fetch("{{ url('konfig/delete') }}/" + id, {

                    method: 'DELETE',

                    headers: {

                        'X-CSRF-TOKEN': '{{ csrf_token() }}'

                    }

                })
                .then(res => res.json())
                .then(res => {

                    if (res.success) {
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