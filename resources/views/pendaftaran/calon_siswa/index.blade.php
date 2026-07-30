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

    <div class="flex gap-2 mb-3">
        <a href="{{ route('calon-siswa.create') }}"
            class="btn btn-primary">
            + Tambah Calon Siswa
        </a>
    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12">
            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">
                    <h2 class="font-medium text-base mr-auto">
                        Daftar Calon Siswa
                    </h2>
                </div>

                <div class="p-5">

                    {{-- Filter --}}
                    <div class="grid grid-cols-12 gap-4 mb-5">

                        {{-- Nama / No Daftar --}}
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">
                                Nama / No. Daftar
                            </label>

                            <input
                                type="text"
                                id="filter-nama"
                                class="form-control"
                                placeholder="Cari nama atau no. daftar...">
                        </div>

                        {{-- Tahun Ajaran --}}
                        <div class="col-span-12 md:col-span-3">
                            <label class="form-label">
                                Tahun Ajaran
                            </label>

                            <select id="filter-tahun-ajaran" class="form-control">
                                <option value="">Semua Tahun Ajaran</option>

                                @foreach ($tahunAjaran as $tahun)
                                    <option value="{{ $tahun->id }}">
                                        {{ $tahun->thn_ajaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Jurusan --}}
                        <div class="col-span-12 md:col-span-2">
                            <label class="form-label">
                                Jurusan
                            </label>

                            <select id="filter-jurusan" class="form-control">
                                <option value="">Semua Jurusan</option>

                                @foreach ($jurusan as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_jurusan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Gelombang --}}
                        <div class="col-span-12 md:col-span-2">
                            <label class="form-label">
                                Gelombang
                            </label>

                            <select id="filter-gelombang" class="form-control">
                                <option value="">Semua Gelombang</option>

                                @foreach ($gelombang as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->nama_gelombang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tombol Reset --}}
                        <div class="col-span-12 md:col-span-2 flex items-end">
                            <button
                                type="button"
                                id="btn-cari"
                                class="btn btn-secondary w-full">

                                Cari
                            </button>
                        </div>

                    </div>

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


@include('pendaftaran.calon_siswa.modal_daftar_ulang.modal-daftar-ulang')



<script>
    let table = new Tabulator("#table-calon-siswa", {

        ajaxURL: "{{ route('calon-siswa.data') }}",


        layout: "fitDataStretch",
        height: "500px",
        //width: "700px",
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
                title: "Nama Lengkap",
                field: "nama_lengkap",
                width: 200
            },

            {
                title: "No Daftar",
                field: "no_daftar",
                width: 150
            },

            {
                title: "Status",
                field: "status_daftar",
                width: 100
            },

            {
                title: "No HP",
                field: "no_hp",
                width: 150
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
                width: 200,


                formatter: function(cell) {
                    let data = cell.getData();

                    return `

                    <div class="flex gap-2">

                        <a href="{{ url('calon-siswa/edit') }}/${data.id}"
                            target="_blank"
                            class="btn btn-primary btn-sm">

                                Edit

                        </a>

                        <button
                            class="btn btn-danger btn-sm"

                            onclick='deleteData(${data.id})'>

                            Hapus

                        </button>

                         @if(isset($side) && $side === 'daftar-ulang')
                            <button
                                type="button"
                                class="btn btn-success btn-sm"
                                onclick='openDaftarUlang(${JSON.stringify(data)})'>
                                Daftar Ulang
                            </button>
                        @endif
               
                    </div>

                `;
                }
            }
        ]
    });


    function openDaftarUlang(data) {
    //const row = table.getRow(id);

        console.log(data);    
        if (!data) {
            alert("Data calon siswa tidak ditemukan.");
            return;
        }

        document.getElementById("id_cawa").value = data.id ?? "";
        document.getElementById("no_daftar").value = data.no_daftar ?? "";
        document.getElementById("nama").value = data.nama_lengkap ?? "";
        document.getElementById("id_jurusan").value = data.id_jurusan ?? "";

        generateNipd();

        const modalElement = document.querySelector("#modal-daftar-ulang");
        const modal = tailwind.Modal.getOrCreateInstance(modalElement);

        modal.show();
    }

      async function generateNipd() {
        const inputNipd = document.getElementById('nipd');

        inputNipd.value = 'Memuat...';
        inputNipd.disabled = true;

        try {
            const response = await fetch(
                "{{ route('calon-siswa.generate-nipd') }}",
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                }
            );

            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(
                    result.message ?? 'Gagal membuat NIPD.'
                );
            }

            inputNipd.value = result.nipd;

        } catch (error) {
            inputNipd.value = '';

            alert(error.message);
        } finally {
            inputNipd.disabled = false;
        }
    }


    function editData(data) {
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


    function saveData(type) {
        let prefix = type == 'add' ?
            'add_' :
            'edit_';

        let id = type == 'edit' ?
            document.getElementById('edit_id').value :
            '';

        let url = type == 'edit' ?
            "{{ url('calon-siswa/update') }}/" + id :
            "{{ url('calon-siswa/store') }}";

        fetch(url, {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                body: JSON.stringify({

                    nama_lengkap: document.getElementById(prefix + 'nama_lengkap').value,

                    jk: document.getElementById(prefix + 'jk').value,

                    nisn: document.getElementById(prefix + 'nisn').value,

                    no_hp: document.getElementById(prefix + 'no_hp').value,

                    id_jurusan: document.getElementById(prefix + 'id_jurusan').value,

                    id_gelombang: document.getElementById(prefix + 'id_gelombang').value,
                })

            })
            .then(res => res.json())
            .then(res => {

                table.replaceData();

            });
    }


    function deleteData(id) {
        if (confirm('Yakin hapus data?')) {
            fetch("{{ url('calon-siswa/delete') }}/" + id, {

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

    async function simpanDaftarUlang() {

        const btn = document.getElementById("btn-simpan-daftar-ulang");

        btn.disabled = true;
        btn.innerHTML = "Menyimpan...";

        try {

            const form = document.getElementById("form-daftar-ulang");

            const formData = new FormData(form);

            const response = await fetch(
                "{{ route('calon-siswa.save-daftar-siswa') }}",
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                    },
                    body: formData
                }
            );

            const result = await response.json();

            if (!response.ok || result.success === false) {

                alert(result.message);

                btn.disabled = false;
                btn.innerHTML = "Simpan";

                return;
            }

            alert(result.message);

            form.reset();

            table.replaceData();

            tailwind.Modal
                .getOrCreateInstance(
                    document.getElementById("modal-daftar-ulang")
                )
                .hide();

        } catch (err) {

            console.error(err);

            alert("Terjadi kesalahan.");

        } finally {

            btn.disabled = false;
            btn.innerHTML = "Simpan";

        }

    }


document.getElementById("btn-cari").addEventListener("click", function () {
    const params = {
        search: document.getElementById("filter-nama").value,
        id_thn_ajaran: document.getElementById("filter-tahun-ajaran").value,
        id_jurusan: document.getElementById("filter-jurusan").value,
        id_gelombang: document.getElementById("filter-gelombang").value,
    };

    table.setData(
        "{{ route('calon-siswa.data') }}",
        params,
        "GET"
    );
});
</script>

@endsection