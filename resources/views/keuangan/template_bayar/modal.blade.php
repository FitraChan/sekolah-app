<div id="modal-form" class="modal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <input type="hidden" id="id">

            <div class="modal-header">
                <h2 class="font-medium text-base">
                    Template Bayar
                </h2>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Tahun Ajaran</label>

                    <select
                        id="id_tahun"
                        class="form-select">

                        @foreach($tahun as $row)
                            <option value="{{ $row->id }}">
                                {{ $row->thn_ajaran }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label>Jurusan</label>

                    <select
                        id="id_jurusan"
                        class="form-select">

                        @foreach($jurusan as $row)
                            <option value="{{ $row->id }}">
                                {{ $row->nama_jurusan }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label>Gelombang</label>

                    <select
                        id="id_gelombang"
                        class="form-select">

                        @foreach($gelombang as $row)
                            <option value="{{ $row->id }}">
                                {{ $row->nama_gelombang }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="mb-3">
                    <label>Jenis Kelas</label>

                    <select
                        id="jns_kelas"
                        class="form-select">

                        <option value="1">
                            Reguler
                        </option>

                        <option value="2">
                            Karyawan
                        </option>

                    </select>
                </div>

                <div class="mb-3">
                    <label>Keterangan</label>

                    <input
                        type="text"
                        id="keterangan"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label>Status</label>

                    <select
                        id="sts"
                        class="form-select">

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Tidak Aktif
                        </option>

                    </select>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    onclick="saveData()"
                    class="btn btn-primary">

                    Simpan

                </button>

            </div>

        </div>

    </div>

</div>