@extends('layout.main')

@section('tittle')
Dashboard
@endsection

@section('top-nav')
<ol class="breadcrumb">
    <li class="breadcrumb-item">Dashboard</li>
</ol>
@endsection

@section('body')

<div class="max-w-7xl mx-auto p-6">

<button class="btn btn-primary mb-3"
        data-tw-toggle="modal"
        data-tw-target="#modal-add-role">

    + Tambah Role

</button>

    <div class="box p-5">

        <div id="table-role"></div>

    </div>

</div>

<div id="modal-add-role"
     class="modal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="font-medium text-base mr-auto">
                    Tambah Role
                </h2>

            </div>

            <div class="modal-body">
                <div class="mb-4">

                <label class="form-label">Nama Role</label>

                <input type="text"
                       id="role_name"
                       class="form-control"
                       placeholder="contoh: kepala sekolah">
                </div>
            </div>

            <div class="modal-footer">

                <button type="button"
                        data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-24 mr-1">

                    Batal

                </button>

                <button type="button"
                        onclick="addRole()"
                        class="btn btn-primary w-24">

                    Simpan

                </button>

            </div>

        </div>

    </div>

</div>

<div id="warning-modal-preview"
    class="modal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="font-medium text-base mr-auto">
                    Edit Role User
                </h2>

            </div>

            <div class="modal-body">

                <input type="hidden" id="user_id">

                <div class="mb-4">

                    <label class="form-label">
                        Nama User
                    </label>

                    <input type="text"
                        id="name"
                        class="form-control"
                        readonly>

                </div>

                <div class="mb-4">

                    <label class="form-label">
                        Role
                    </label>

                    <select id="role" class="form-select">

                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                    data-tw-dismiss="modal"
                    class="btn btn-outline-secondary w-24 mr-1">

                    Batal

                </button>

                <button type="button"
                    onclick="updateRole()"
                    class="btn btn-primary w-24">

                    Simpan

                </button>

            </div>

        </div>

    </div>

</div>
<!-- MODAL DI LUAR BOX -->

<script>
    var table = new Tabulator("#table-role", {

        ajaxURL: "{{ route('role.data') }}",

        layout: "fitColumns",

        pagination: true,

        paginationSize: 10,

        movableColumns: true,

        columns: [

         {
            title: "No",

            formatter: "rownum",

            hozAlign: "center",

            width: 80,
        },

            {
                title: "Nama",
                field: "name"
            },

            {
                title: "Email",
                field: "email"
            },

            {
                title: "Role",
                field: "role"
            },

            {
                title: "Aksi",
                field: "action",
                hozAlign: "left",
                formatter: function(cell) {
                    let row = cell.getData();

                    

                    return `

        <a href="javascript:;"
           data-tw-toggle="modal"
           data-tw-target="#warning-modal-preview"

           onclick="editRole(
                ${row.id},
                '${row.name}',
                '${row.role}'
           )"

           class="btn btn-primary">

            Edit

        </a>

    `;
                }
            },

        ],

    });

    function editRole(id, name, role) {
      
            document.getElementById('user_id').value = id;
            document.getElementById('name').value = name;

            let select = document.getElementById('role');

            for (let i = 0; i < select.options.length; i++) {

                if (select.options[i].value === role) {
                    select.selectedIndex = i;
                }
            }

           document
            .getElementById('warning-modal-preview')
            .classList.remove('hidden');
    }

    function closeModal() {
        document
            .getElementById('warning-modal-preview')
            .classList.add('hidden');
    }

    function updateRole() {
        let id = document.getElementById('user_id').value;
        let role = document.getElementById('role').value;

        fetch('/role/update/' + id, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },

                body: JSON.stringify({
                    role: role
                })

            })

            .then(response => response.json())

            .then(data => {

                alert(data.message);

                closeModal();

                table.replaceData();

            });

    }

    function addRole()
    {
        let name = document.getElementById('role_name').value;

        fetch('/role/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);

             document
            .getElementById('modal-add-role')
            .classList.add('hidden');
        });
    }

    
</script>

@endsection