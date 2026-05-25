<form action="{{ route('calon-siswa.update.orangtua', $rows->id ?? 0) }}"
      method="POST">

    @csrf
<div class="box p-5">

    <h2 class="text-lg font-medium mb-5">
        Orang Tua
    </h2>

    <div class="grid grid-cols-12 gap-4">

        <!-- Nama Lengkap Ortu -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Nama Lengkap Ortu
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Nama Lengkap Ortu"
                   name="nama_ayah"
                   value="{{ $rows->nama_ayah }}">

        </div>

        <!-- Pekerjaan -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Pekerjaan
            </label>

            <select class="form-control"
                    name="pekerjaan_ayah">

                <option>--Pilih Pekerjaan--</option>

                @if(count($jobs))

                    @foreach($jobs as $list)

                        <option value="{{ $list['id'] }}"
                            {{ $list['id'] == $rows->id_kerja_ayah ? 'selected' : '' }}>

                            {{ $list['nama_pekerjaan'] }}

                        </option>

                    @endforeach

                @endif

            </select>

        </div>

        <!-- Alamat Orang Tua -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                Alamat Orang Tua / Wali
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="Alamat Ortu"
                   name="alamat_ayah"
                   value="{{ $rows->alamat_ayah }}">

        </div>

        <!-- No HP Ortu -->
        <div class="col-span-12 md:col-span-6">

            <label class="form-label">
                No. HP Ortu
            </label>

            <input type="text"
                   class="form-control"
                   placeholder="No. HP Ayah"
                   name="hp_ayah"
                   value="{{ $rows->hp_ayah }}">

        </div>

        <!-- Dari Mana Tahu SMK -->
        <div class="col-span-12">

            <label class="form-label">
                Dari Mana Tahu SMK TI
            </label>

            <select name="tahu_smk_dari_mana"
                    class="form-control">

                <option value="Media Sosial"
                    {{ $rows->tahu_smk_dari_mana == 'Media Sosial' ? 'selected' : '' }}>
                    Media Sosial
                </option>

                <option value="Media Elektornik"
                    {{ $rows->tahu_smk_dari_mana == 'Media Elektornik' ? 'selected' : '' }}>
                    Media Elektronik
                </option>

                <option value="Website"
                    {{ $rows->tahu_smk_dari_mana == 'Website' ? 'selected' : '' }}>
                    Website
                </option>

                <option value="Teman/Saudara"
                    {{ $rows->tahu_smk_dari_mana == 'Teman/Saudara' ? 'selected' : '' }}>
                    Teman/Saudara
                </option>

                <option value="Sosialisasi"
                    {{ $rows->tahu_smk_dari_mana == 'Sosialisasi' ? 'selected' : '' }}>
                    Sosialisasi
                </option>

            </select>

        </div>

        <button type="submit"
                        class="btn btn-primary rounded-xl px-8">

                    Simpan

                </button>


    </div>

</div>

</form>