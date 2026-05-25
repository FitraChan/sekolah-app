<form action="{{ route('calon-siswa.update.updateRegistrasiSiswa', $rows->id ?? '') }}"
    method="POST">

    @csrf

    <div class="max-w-7xl mx-auto">

        <!-- CARD -->
        <div class="box shadow-sm border border-slate-200 rounded-2xl overflow-hidden">

            <!-- HEADER -->
            <div class="px-6 py-5 border-b bg-slate-50">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-xl font-semibold text-slate-700">
                            Data Calon Siswa
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Form biodata dan informasi pendaftaran siswa
                        </p>

                    </div>

                </div>

            </div>

            <!-- BODY -->
            <div class="p-6">

                <!-- INFORMASI PENDAFTARAN -->
                <div class="p-5">

                    <div class="flex items-center mb-5">

                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center mr-3">

                            <i data-lucide="file-text"
                                class="w-5 h-5 text-primary"></i>

                        </div>

                        <div>

                            <h3 class="font-semibold text-lg">
                                Informasi Pendaftaran
                            </h3>

                            <p class="text-slate-500 text-sm">
                                Data administrasi pendaftaran siswa
                            </p>

                        </div>

                    </div>

                    <div class="grid grid-cols-12 gap-5">

                        <!-- GELombang -->
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Gelombang
                            </label>

                            <select name="id_gelombang"
                                class="form-select rounded-xl">

                                <option value="">
                                    -- Pilih Gelombang --
                                </option>

                                @foreach($gel as $list)

                               <option value="{{ $list->id }}"
                                {{ old('id_gelombang', $rows->id_gelombang ?? '') == $list->id ? 'selected' : '' }}>

                                {{ $list->nama_gelombang }}

                            </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- TAHUN -->
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Tahun Ajaran
                            </label>

                            <select name="id_thn_ajaran"
                                class="form-select rounded-xl">

                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                @foreach($thn as $list)

                              <option value="{{ $list->id }}"
                                {{ old('id_thn_ajaran', $rows->id_thn_ajaran ?? '') == $list->id ? 'selected' : '' }}>

                                {{ $list->thn_ajaran }}

                            </option>
                                @endforeach

                            </select>

                        </div>

                        <!-- NO DAFTAR -->
                        <div class="col-span-12 md:col-span-4">

                            <label class="form-label font-medium">
                                No. Daftar
                            </label>

                             <input type="text"
                               name="no_daftar"
                               class="form-control rounded-xl"
                               value="{{ old('no_daftar', $rows->no_daftar ?? '') }}">
                        </div>

                        <!-- TGL -->
                        <div class="col-span-12 md:col-span-4">

                            <label class="form-label font-medium">
                                Tanggal Daftar
                            </label>

                            <input type="date"
                               name="tgl_daftar"
                               class="form-control rounded-xl"
                               value="{{ old('tgl_daftar', $rows->tgl_daftar ?? '') }}">
                        </div>

                        <!-- STATUS -->
                        <div class="col-span-12 md:col-span-4">

                            <label class="form-label font-medium">
                                Status Siswa
                            </label>

                            <select name="status_daftar"
                                class="form-select rounded-xl">

                                <option value="1" {{ old('status_daftar', $rows->status_daftar ?? '') == 1 ? 'selected' : '' }}>
                                    Aktif
                                </option>

                                <option value="0" {{ old('status_daftar', $rows->status_daftar ?? '') == 0 ? 'selected' : '' }}>
                                    Tidak Aktif
                                </option>

                            </select>

                        </div>

                        <!-- JURUSAN -->
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Jurusan Yang Dipilih
                            </label>

                            <select name="id_jurusan"
                                class="form-select rounded-xl">

                                <option value="">
                                    -- Pilih Jurusan --
                                </option>

                                @foreach($lists as $list)

                                <option value="{{ $list->id }}"
                                    {{ old('id_jurusan', $rows->id_jurusan ?? '') == $list->id ? 'selected' : '' }}>

                                    {{ $list->nama_jurusan }}

                                </option>

                                @endforeach

                            </select>

                        </div>

                        <!-- TEMPAT -->
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Tempat Daftar
                            </label>

                            <input type="text"
                                name="tmp_daftar"
                                class="form-control rounded-xl"
                                value="{{ old('tmp_daftar', $rows->tmp_daftar ?? '') }}">

                        </div>

                    </div>

                </div>

                <!-- BIODATA -->
                <div class="mt-10">

                    <div class="flex items-center mb-5">

                        <div class="w-10 h-10 rounded-full bg-warning/10 flex items-center justify-center mr-3">

                            <i data-lucide="user"
                                class="w-5 h-5 text-warning"></i>

                        </div>

                        <div>

                            <h3 class="font-semibold text-lg">
                                Biodata Siswa
                            </h3>

                            <p class="text-slate-500 text-sm">
                                Informasi identitas siswa
                            </p>

                        </div>

                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-12 gap-5">

                            <!-- NAMA -->
                            <div class="col-span-12 lg:col-span-6">

                                <label class="form-label text-sm font-medium">
                                    Nama Lengkap
                                </label>

                                <input type="text"
                                    name="nama_lengkap"
                                    class="form-control h-11 rounded-lg"
                                    value="{{ old('nama_lengkap', $rows->nama_lengkap ?? '') }}">

                            </div>

                            <!-- JK -->
                            <div class="col-span-12 lg:col-span-6">

                                <label class="form-label text-sm font-medium mb-2 block">
                                    Jenis Kelamin
                                </label>

                                <div class="flex flex-wrap gap-3">

                                    <label class="flex items-center gap-2 border border-slate-200 rounded-lg px-4 h-11 cursor-pointer">

                                        <input type="radio"
                                            name="jk"
                                            value="L"
                                            {{ old('jk', $rows->jk ?? '') == 'L' ? 'checked' : '' }}>

                                        <span class="text-sm">
                                            Laki-Laki
                                        </span>

                                    </label>

                                    <label class="flex items-center gap-2 border border-slate-200 rounded-lg px-4 h-11 cursor-pointer">

                                        <input type="radio"
                                            name="jk"
                                            value="P"
                                            {{ old('jk', $rows->jk ?? '') == 'P' ? 'checked' : '' }}>

                                        <span class="text-sm">
                                            Perempuan
                                        </span>

                                    </label>

                                </div>

                            </div>

                            <!-- AGAMA -->
                            <div class="col-span-12 md:col-span-6 lg:col-span-4">

                                <label class="form-label text-sm font-medium">
                                    Agama
                                </label>

                                <select name="id_agama"
                                    class="form-select h-11 rounded-lg">

                                    <option value="">
                                        -- Pilih Agama --
                                    </option>

                                    @foreach($agama as $list)

                                    <option value="{{ $list->id }}"
                                        {{ old('id_agama', $rows->id_agama ?? '') == $list->id ? 'selected' : '' }}>

                                        {{ $list->nama_agama }}

                                    </option>

                                    @endforeach

                                </select>

                            </div>

                            <!-- TEMPAT -->
                            <div class="col-span-12 md:col-span-6 lg:col-span-4">

                                <label class="form-label text-sm font-medium">
                                    Tempat Lahir
                                </label>

                                <input type="text"
                                    name="tmp_lahir"
                                    class="form-control h-11 rounded-lg"
                                    value="{{ old('tmp_lahir', $rows->tmp_lahir ?? '') }}">

                            </div>

                            <!-- TGL -->
                            <div class="col-span-12 md:col-span-6 lg:col-span-4">

                                <label class="form-label text-sm font-medium">
                                    Tanggal Lahir
                                </label>

                                <input type="date"
                                    name="tgl_lahir"
                                    class="form-control h-11 rounded-lg"
                                    value="{{ old('tgl_lahir', $rows->tgl_lahir ?? '') }}">

                            </div>

                            <!-- ALAMAT -->
                            <div class="col-span-12">

                                <label class="form-label text-sm font-medium">
                                    Alamat Lengkap
                                </label>

                                <textarea name="alamat"
                                    rows="3"
                                    class="form-control rounded-lg resize-none">{{ old('alamat', $rows->alamat ?? '') }}</textarea>

                            </div>

                            <!-- DUSUN -->
                            <div class="col-span-12 md:col-span-6 lg:col-span-3">

                                <label class="form-label text-sm font-medium">
                                    Dusun
                                </label>

                                <input type="text"
                                    name="dusun"
                                    class="form-control h-11 rounded-lg"
                                    value="{{ old('dusun', $rows->dusun ?? '') }}">

                            </div>

                            <!-- DESA -->
                            <div class="col-span-12 md:col-span-6 lg:col-span-3">

                                <label class="form-label text-sm font-medium">
                                    Desa
                                </label>

                                <input type="text"
                                    name="desa"
                                    class="form-control h-11 rounded-lg"
                                    value="{{ old('desa', $rows->desa ?? '') }}">

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="px-6 py-5 border-t bg-slate-50 flex justify-end gap-3">

                <a href="{{ route('calon-siswa.index') }}"
                    class="btn btn-outline-secondary rounded-xl">

                    Kembali

                </a>

                <button type="submit"
                    class="btn btn-primary rounded-xl px-8">

                    Simpan Perubahan

                </button>

            </div>

        </div>

    </div>

</form>