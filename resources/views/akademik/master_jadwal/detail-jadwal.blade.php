<div class="max-w-7xl mx-auto p-6">

    <div class="flex gap-2 mb-3">

        <button
            class="btn btn-success"
            onclick="saveDetailJadwal()">

            <i data-lucide="save" class="w-4 h-4 mr-1"></i>

            Simpan Detail Jadwal

        </button>


        


    </div>

    <div class="grid grid-cols-12 gap-6 mt-5">

        <div class="intro-y col-span-12">

            <div class="intro-y box">

                <div class="flex flex-col sm:flex-row items-center p-5 border-b border-slate-200/60">

                    <h2 class="font-medium text-base mr-auto">
                        Detail Penjadwalan
                    </h2>

                </div>

                <div class="p-5">

                    <!-- FILTER -->

                    <div class="flex flex-wrap gap-3 mb-4">

                        <select id="filter_tahun_detail" class="form-select w-56">

                            <option value="">
                                Semua Tahun Ajaran
                            </option>

                            @foreach($tahun as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->thn_ajaran }}
                            </option>

                            @endforeach

                        </select>

                        <select id="filter_jurusan_detail" class="form-select w-56">

                            <option value="">
                                Semua Jurusan
                            </option>

                            @foreach($jurusan as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->nama_jurusan }}
                            </option>

                            @endforeach

                        </select>

                        <select id="filter_kelas_detail" class="form-select w-56">

                            <option value="">
                                Semua Kelas
                            </option>

                            @foreach($kelas as $item)

                            <option value="{{ $item->id }}">
                                {{ $item->nama_kelas }}
                            </option>

                            @endforeach

                        </select>

                        <button
                            class="btn btn-primary"
                            onclick="loadDetailData()">

                            Cari

                        </button>

                        <button
                            class="btn btn-secondary"
                            onclick="resetDetailFilter()">

                            Reset

                        </button>

                    </div>

                    <div class="overflow-x-auto">

                        <div id="table-detail-jadwal"></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

const hariOptions = {

    1 : 'Senin',
    2 : 'Selasa',
    3 : 'Rabu',
    4 : 'Kamis',
    5 : 'Jumat',
    6 : 'Sabtu',
    7 : 'Minggu'

};

const jamOptions = @json(
    $jam->mapWithKeys(function ($item) {
        return [
            $item->id =>
                'Jam Ke-' . $item->jam_ke .
                ' (' .
                $item->jam_awal .
                ' - ' .
                $item->jam_akhir .
                ')'
        ];
    })
);

let changedRowsDetail = [];

let tableDetail = new Tabulator("#table-detail-jadwal", {

    ajaxURL: "{{ route('detail-jadwal.data') }}",

    ajaxParams: {},

    layout: "fitDataStretch",

    pagination: true,

    paginationSize: 20,

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
            title: "Tahun",
            field: "tahun_ajaran",
            width: 150
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
            field: "nama_gtk",
            width: 250
        },

        {
            title: "Hari",
            field: "id_hari",
            width: 150,

            editor: "list",

            editorParams: {

                values: hariOptions,

                autocomplete: true,

                listOnEmpty: true

            },

            formatter: function(cell){

                return hariOptions[cell.getValue()] ?? "-";

            }
        },

        {
            title: "Jam Pelajaran",
            field: "id_jam",
            width: 300,

            editor: "list",

            editorParams: {

                values: jamOptions,

                autocomplete: true,

                listOnEmpty: true

            },

            formatter: function(cell){

                return jamOptions[cell.getValue()] ?? "-";

            }
        },

        {
            title: "Jam Ke",
            field: "jam_ke",
            width: 100,
            hozAlign: "center"
        },

        {
            title: "Waktu",

            width: 150,

            formatter: function(cell){

                let row = cell.getData();

                return (row.jam_awal ?? '') +
                       ' - ' +
                       (row.jam_akhir ?? '');

            }
        }

    ]

});

tableDetail.on("cellEdited", function(cell){

    let data = cell.getRow().getData();

    let idx = changedRowsDetail.findIndex(
        x => x.id == data.id
    );

    if(idx === -1){

        changedRowsDetail.push({...data});

    }else{

        changedRowsDetail[idx] = {...data};

    }

});

function loadDetailData()
{
    tableDetail.setData(

        "{{ route('detail-jadwal.data') }}",

        {

            id_tahun:
                document.getElementById('filter_tahun_detail').value,

            id_jurusan:
                document.getElementById('filter_jurusan_detail').value,

            id_kelas:
                document.getElementById('filter_kelas_detail').value

        }

    );
}

function resetDetailFilter()
{
    document.getElementById('filter_tahun_detail').value = '';
    document.getElementById('filter_jurusan_detail').value = '';
    document.getElementById('filter_kelas_detail').value = '';
    loadDetailData();
}

function saveDetailJadwal()
{
    if(changedRowsDetail.length === 0)
    {
        alert('Tidak ada perubahan');

        return;
    }

    console.log(changedRowsDetail);

    fetch(
        "{{ route('detail-jadwal.update') }}",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                data: changedRowsDetail
            })
        }
    )
    .then(res => res.json())
    .then(res => {
        alert(res.msg);
        changedRowsDetail = [];
        tableDetail.replaceData();
    });
}

</script>