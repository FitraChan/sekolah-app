@extends('layout.main')

@section('tittle')
Broadcast
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

    <button class="btn btn-primary mb-4"
        data-tw-toggle="modal"
        data-tw-target="#modal-broadcast">

        + Tambah Broadcast

    </button>

    <div class="box p-5">

        <div id="table-broadcast"></div>

    </div>

    <div class="box p-5">

        <div class="grid grid-cols-12 gap-4">

            <!-- DROPDOWN -->
            <div class="col-span-12 md:col-span-4">

                <label class="form-label">
                    Pilih Status
                </label>

                <select id="status"
        class="form-select"
        onchange="changeBroadcast(this)">

    <option value="">
        -- Pilih Broadcast --
    </option>

    <?php foreach ($broadcast as $row): ?>

        <option value="<?= $row->id ?>"
                data-pesan="<?= htmlspecialchars($row->pesan) ?>">

            <?= $row->judul ?>

        </option>

    <?php endforeach; ?>

</select>

            </div>

            <!-- TEXTAREA -->
            <div class="col-span-12 md:col-span-8">

                <label class="form-label">
                    Catatan
                </label>

                <textarea id="textarea-pesan"
          class="form-control mt-3"
          rows="6"></textarea>

            </div>

        </div>

    </div>

</div>

<!-- MODAL -->

<!-- CKEDITOR -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<div id="modal-broadcast" class="modal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="font-medium text-base mr-auto">
                    Broadcast
                </h2>

            </div>

            <div class="modal-body">

                <input type="hidden" id="id">

                <!-- JUDUL -->
                <div class="mb-3">

                    <label class="form-label">
                        Judul
                    </label>

                    <input type="text"
                        id="judul"
                        class="form-control">

                </div>

                <!-- PESAN -->
                <div>

                    <label class="form-label">
                        Pesan
                    </label>

                    <textarea id="pesan"
                        rows="10"
                        class="form-control"></textarea>

                </div>

            </div>

            <div class="modal-footer">

                <button class="btn btn-primary"
                    onclick="saveData()">

                    Simpan

                </button>

            </div>

        </div>

    </div>

</div>

<script>
    let editorPesan;

    ClassicEditor
        .create(document.querySelector('#pesan'))
        .then(editor => {

            editorPesan = editor;

        })
        .catch(error => {

            console.error(error);

        });

    function editData(data) {
        document.getElementById('id').value = data.id;

        document.getElementById('judul').value = data.judul;

        editorPesan.setData(data.pesan ?? '');

        tailwind.Modal.getOrCreateInstance(
            document.querySelector("#modal-broadcast")
        ).show();
    }

    function saveData() {
        let id = document.getElementById('id').value;

        let url = id ?
            '/broadcast/update/' + id :
            '/broadcast/store';

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    judul: document.getElementById('judul').value,
                    pesan: editorPesan.getData()
                })

            })

            .then(res => res.json())

            .then(res => {

                table.replaceData();

                document.getElementById('id').value = '';

                document.getElementById('judul').value = '';

                editorPesan.setData('');

                tailwind.Modal.getOrCreateInstance(
                    document.querySelector("#modal-broadcast")
                ).hide();

            });
    }
</script>
<script>

     function changeBroadcast(select)
    {
        let option = select.options[select.selectedIndex];

        let pesan = option.getAttribute('data-pesan');

        document.getElementById('textarea-pesan').value = pesan ?? '';
    }
    let table = new Tabulator("#table-broadcast", {

        ajaxURL: "{{ route('broadcast.data') }}",

        layout: "fitColumns",

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                title: "No",
                formatter: "rownum",
                width: 60
            },

            {
                title: "Judul",
                field: "judul"
            },

            {
                title: "Pesan",
                field: "pesan"
            },

            {
                title: "Tanggal",
                field: "tgl_update"
            },

            {
                title: "Action",

                formatter: function(cell) {

                    let data = cell.getData();

                    return `
                        <button
                            class="btn btn-primary btn-sm"
                            onclick='editData(${JSON.stringify(data)})'>

                            Edit

                        </button>

                        <button
                            class="btn btn-danger btn-sm"
                            onclick='deleteData(${data.id})'>

                            Hapus

                        </button>
                    `;
                }
            }
        ]
    });

    function editData(data) {
        document.getElementById('id').value = data.id;

        document.getElementById('judul').value = data.judul;

        document.getElementById('pesan').value = data.pesan;

        tailwind.Modal.getOrCreateInstance(
            document.querySelector("#modal-broadcast")
        ).show();
    }



    function deleteData(id) {
        if (confirm('Hapus data?')) {
            fetch('/broadcast/delete/' + id, {

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