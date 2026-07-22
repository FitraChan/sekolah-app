<div
    id="modal-edit-tahun-ajaran"
    class="modal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Edit Tahun Ajaran
                </h2>
            </div>

            <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                <input
                    type="hidden"
                    id="edit_id_thn"
                >

                <div class="col-span-12">
                    <label
                        for="edit_thn_ajaran"
                        class="form-label"
                    >
                        Tahun Ajaran
                    </label>

                    <input
                        type="text"
                        id="edit_thn_ajaran"
                        class="form-control"
                        placeholder="Contoh: 2026/2027"
                    >

                    <div
                        id="edit_thn_ajaran_error"
                        class="validation-error hidden text-danger text-xs mt-1"
                    ></div>
                </div>

                <div class="col-span-12">
                    <label
                        for="edit_isaktiv"
                        class="form-label"
                    >
                        Status
                    </label>

                    <select
                        id="edit_isaktiv"
                        class="form-control"
                    >
                        <option value="0">
                            Tidak Aktif
                        </option>

                        <option value="1">
                            Aktif
                        </option>
                    </select>

                    <div
                        id="edit_isaktiv_error"
                        class="validation-error hidden text-danger text-xs mt-1"
                    ></div>
                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    data-tw-dismiss="modal"
                    class="btn btn-outline-secondary w-24 mr-1"
                >
                    Batal
                </button>

                <button
                    type="button"
                    id="btn-save-edit"
                    onclick="saveData('edit')"
                    class="btn btn-primary w-24"
                >
                    Simpan
                </button>

            </div>

        </div>

    </div>
</div>