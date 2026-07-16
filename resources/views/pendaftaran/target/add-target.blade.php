<div
    id="modal-add-target"
    class="modal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h2 class="font-medium text-base mr-auto">
                    Tambah Target Pendaftaran
                </h2>

                <button
                    type="button"
                    data-tw-dismiss="modal">

                    <i data-lucide="x" class="w-5 h-5"></i>

                </button>

            </div>

            <form
                id="form-add-target"
                onsubmit="event.preventDefault(); saveData();">

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-12 sm:col-span-6">

                        <label
                            for="add_id_thn_ajaran"
                            class="form-label">

                            Tahun Ajaran

                        </label>

                        <select
                            id="add_id_thn_ajaran"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih Tahun Ajaran
                            </option>

                            @foreach($tahunAjaran as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->thn_ajaran }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-span-12 sm:col-span-6">

                        <label
                            for="add_id_jurusan"
                            class="form-label">

                            Jurusan

                        </label>

                        <select
                            id="add_id_jurusan"
                            class="form-control"
                            required>

                            <option value="">
                                Pilih Jurusan
                            </option>

                            @foreach($jurusan as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama_jurusan }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-span-12 sm:col-span-6">

                        <label
                            for="add_target"
                            class="form-label">

                            Target

                        </label>

                        <input
                            type="number"
                            id="add_target"
                            class="form-control"
                            min="0"
                            value="0"
                            placeholder="Masukkan target"
                            required>

                    </div>

                    

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        data-tw-dismiss="modal"
                        class="btn btn-outline-secondary w-20 mr-1">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary w-20">

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>