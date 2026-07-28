<div id="modal-daftar-ulang"
    class="modal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h2 class="font-medium text-base mr-auto">
                    Form Daftar Ulang
                </h2>

                <button
                    type="button"
                    data-tw-dismiss="modal"
                    class="btn btn-outline-secondary">
                    X
                </button>
            </div>

            <form id="form-daftar-ulang">

                @csrf

                <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">
                            No Kwitansi
                        </label>

                        <input
                            type="text"
                            id="no_kwitansi"
                            name="no_kwitansi"
                            class="form-control"
                            placeholder="Masukkan no kwitansi">
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">
                           Set NIPD
                        </label>

                        <input
                            type="text"
                            id="nipd"
                            name="nipd"
                            class="form-control"
                            placeholder="Masukkan nipd">

                        <input
                            type="hidden"
                            id="id_cawa"
                            name="id_cawa"
                            class="form-control"
                            placeholder="Masukkan ID Cawa">
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">
                            No Daftar
                        </label>

                        <input
                            type="text"
                            id="no_daftar"
                            name="no_daftar"
                            class="form-control"
                            placeholder="Masukkan no daftar">
                    </div>

                    <div class="col-span-12 sm:col-span-6">
                        <label class="form-label">
                            Nama
                        </label>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            placeholder="Nama calon siswa">
                    </div>

                    <div class="col-span-12">
                        <label class="form-label">
                            Jurusan
                        </label>

                        <select
                            id="id_jurusan"
                            name="id_jurusan"
                            class="form-control">

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
                        id="btn-simpan-daftar-ulang"
                        class="btn btn-primary w-24"
                        onclick="simpanDaftarUlang()">
                        Simpan
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>