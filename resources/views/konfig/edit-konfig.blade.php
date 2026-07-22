<div id="modal-edit-konfig" class="modal" tabindex="-1" aria-hidden="true">

<div class="modal-dialog modal-lg">

    <div class="modal-content">

        <input type="hidden" id="edit_id">

        <div class="modal-header">

            <h2 class="font-medium text-base mr-auto">
                Edit Konfigurasi
            </h2>

        </div>

        <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

            <div class="col-span-12 sm:col-span-6">

                <label class="form-label">
                    ID Tahun
                </label>

                <input
                    type="number"
                    id="edit_id_tahun"
                    class="form-control">

            </div>

            <!-- <div class="col-span-12 sm:col-span-6">

                <label class="form-label">
                    ID Gelombang
                </label>

                <input
                    type="number"
                    id="edit_id_gelombang"
                    class="form-control">

            </div> -->

            <div class="col-span-12 sm:col-span-6">

                <label class="form-label">
                    Semester
                </label>

                <select
                    id="edit_smt"
                    class="form-select">

                    <option value="1">Ganjil</option>
                    <option value="2">Genap</option>

                </select>

            </div>

            <div class="col-span-12 sm:col-span-6">

                <label class="form-label">
                    ID Tahun PPDB
                </label>

                <input
                    type="number"
                    id="edit_id_thn_ppdb"
                    class="form-control">

            </div>

        </div>

        <div class="modal-footer">

            <button
                type="button"
                data-tw-dismiss="modal"
                class="btn btn-outline-secondary w-24 mr-1">

                Batal

            </button>

            <button
                type="button"
                onclick="saveDataKonfig()"
                class="btn btn-primary w-24">

                Update

            </button>

        </div>

    </div>

</div>


</div>
