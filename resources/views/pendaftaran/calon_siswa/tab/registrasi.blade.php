
<form action="{{ route('calon-siswa.update.registrasi', $rows->id ?? 0) }}"
      method="POST">

    @csrf
<div class="box p-5">

    <h2 class="text-lg font-medium mb-5">
        Registrasi
    </h2>

    <div class="grid grid-cols-12 gap-4">

        <!-- Nama Sekolah -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Nama Sekolah
                <small>(SMP/MTs)</small>
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Nama Sekolah"
                   name="sekolah_asal"
                   value="{{ $rows->nama_sekolah_asal }}">

        </div>

        <!-- Alamat Sekolah -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Alamat Asal Sekolah
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Alamat Asal Sekolah"
                   name="alamat_sekolah_asal"
                   value="{{ $rows->alamat_sekolah_asal }}">

        </div>

        <!-- Kabupaten Sekolah -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Kabupaten Sekolah Asal
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Kabupaten Asal Sekolah"
                   name="kabupaten_sekolah_asal"
                   value="{{ $rows->kab_sekolah }}">

        </div>

        <!-- Provinsi Sekolah -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Provinsi Sekolah Asal
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Provinsi Asal Sekolah"
                   name="provinsi_sekolah_asal"
                   value="{{ $rows->prov_sekolah }}">

        </div>


         <button type="submit"
                        class="btn btn-primary rounded-xl px-8">

                    Simpan

                </button>

    </div>

</div>
</form>