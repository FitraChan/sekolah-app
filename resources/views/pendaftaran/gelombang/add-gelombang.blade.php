<div id="modal-add-gelombang" class="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Tambah Gelombang
                </h2>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        ID Tahun
                    </label>

                    <input type="text"
                           id="add_id_tahun"
                           class="form-control"
                           placeholder="Masukkan ID Tahun">
                </div>

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        Nama Gelombang
                    </label>

                    <input type="text"
                           id="add_nama_gelombang"
                           class="form-control"
                           placeholder="Contoh: Gelombang 1">
                </div>

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        Tanggal Awal
                    </label>

                    <input type="date"
                           id="add_awal"
                           class="form-control">
                </div>

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        Tanggal Akhir
                    </label>

                    <input type="date"
                           id="add_akhir"
                           class="form-control">
                </div>

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        Status
                    </label>

                    <select id="add_is_current" class="form-select">

                        <option value="1">
                            Aktif
                        </option>

                        <option value="0">
                            Tidak Aktif
                        </option>

                    </select>
                </div>

                <div class="col-span-12 sm:col-span-6">
                    <label class="form-label">
                        Urutan (Idx)
                    </label>

                    <input type="number"
                           id="add_idx"
                           class="form-control"
                           value="0">
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-24 mr-1">

                    Batal

                </button>

                <button type="button"
                        onclick="saveData()"
                        class="btn btn-primary w-24">

                    Simpan

                </button>

            </div>

        </div>
    </div>
</div>