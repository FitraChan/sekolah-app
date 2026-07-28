<form action="{{ route('siswa.update-akademik', $rows->id ?? '') }}"
    method="POST">

    @csrf
   

    <div class="max-w-7xl mx-auto">

        <div class="box shadow-sm border border-slate-200 rounded-2xl overflow-hidden">

            {{-- HEADER --}}
            <div class="px-6 py-5 border-b bg-slate-50">

                <div class="flex items-center">

                    <div class="w-10 h-10 rounded-full bg-primary/10
                        flex items-center justify-center mr-3">

                        <i data-lucide="user"
                            class="w-5 h-5 text-primary"></i>

                    </div>

                    <div>
                        <h2 class="text-xl font-semibold text-slate-700">
                            Data Siswa
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Perbarui data akademik dan biodata siswa
                        </p>
                    </div>

                </div>

            </div>

            {{-- BODY --}}
            <div class="p-6">

                @if ($errors->any())
                <div class="alert alert-danger mb-5">

                    <div class="font-medium mb-2">
                        Data belum dapat disimpan:
                    </div>

                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
                @endif

                {{-- DATA AKADEMIK --}}
                <div class="px-6 py-5 border-b bg-slate-50">


                    <div class="flex items-center mb-5">

                        <div class="w-10 h-10 rounded-full bg-primary/10
                            flex items-center justify-center mr-3">

                            <i data-lucide="graduation-cap"
                                class="w-5 h-5 text-primary"></i>

                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Data Akademik
                            </h3>

                            <p class="text-slate-500 text-sm">
                                Informasi akademik siswa
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-12 gap-5">

                        {{-- NIPD --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                NIPD
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="nipd"
                                class="form-control rounded-xl
                                    @error('nipd') border-danger @enderror"
                                value="{{ old('nipd', $rows->nipd ?? '') }}"
                                placeholder="Masukkan NIPD siswa"
                                required>

                            @error('nipd')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- TAHUN AJARAN --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Tahun Ajaran
                                <span class="text-danger">*</span>
                            </label>

                            <select name="id_thn_ajaran"
                                class="form-select rounded-xl
                                    @error('id_thn_ajaran') border-danger @enderror"
                                required>

                                <option value="">
                                    -- Pilih Tahun Ajaran --
                                </option>

                                @foreach ($tahunAjaran as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                            'id_thn_ajaran',
                                            $rows->id_thn_ajaran ?? ''
                                        ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->thn_ajaran }}

                                </option>
                                @endforeach

                            </select>

                            @error('id_thn_ajaran')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- JURUSAN --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Jurusan
                            </label>

                            <select name="id_jurusan"
                                class="form-select rounded-xl
                                    @error('id_jurusan') border-danger @enderror">

                                <option value="">
                                    -- Pilih Jurusan --
                                </option>

                                @foreach ($jurusan as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                            'id_jurusan',
                                            $rows->id_jurusan ?? ''
                                        ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_jurusan }}

                                </option>
                                @endforeach

                            </select>

                            @error('id_jurusan')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- KELAS --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Kelas
                                <span class="text-danger">*</span>
                            </label>

                            <select name="id_kelas"
                                class="form-select rounded-xl
                                    @error('id_kelas') border-danger @enderror"
                                required>

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                @foreach ($kelas as $item)
                                <option value="{{ $item->idx }}"
                                    {{ old(
                                            'id_kelas',
                                            $rows->id_kelas ?? ''
                                        ) == $item->idx ? 'selected' : '' }}>

                                    {{ $item->nama_kelas }}

                                </option>
                                @endforeach

                            </select>

                            @error('id_kelas')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>



                        {{-- STATUS --}}
                        <div class="col-span-12 md:col-span-4">

                            <label class="form-label font-medium">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select name="sts_siswa"
                                class="form-control">

                                @foreach($status_siswa as $baris)

                                <option value="{{ $baris->id }}"
                                    {{ ($rows->status_siswa == $baris->id) ? 'selected' : '' }}>

                                    {{ $baris->status_siswa }}

                                </option>

                                @endforeach

                            </select>

                            @error('status')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                    </div>

                </div>

                <hr class="my-8">

                {{-- BIODATA SISWA --}}
                <div class="px-6 py-5 border-b bg-slate-50">

                    <div class="flex items-center mb-5">

                        <div class="w-10 h-10 rounded-full bg-warning/10
                            flex items-center justify-center mr-3">

                            <i data-lucide="user"
                                class="w-5 h-5 text-warning"></i>

                        </div>

                        <div>
                            <h3 class="font-semibold text-lg">
                                Biodata Siswa
                            </h3>

                            <p class="text-slate-500 text-sm">
                                Informasi identitas dan alamat siswa
                            </p>
                        </div>

                    </div>

                    <div class="grid grid-cols-12 gap-5">

                        {{-- NAMA LENGKAP --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Nama Lengkap
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                name="nama_lengkap"
                                class="form-control rounded-xl
                                    @error('nama_lengkap') border-danger @enderror"
                                value="{{ old(
                                    'nama_lengkap',
                                    $rows->nama_lengkap ?? ''
                                ) }}"
                                placeholder="Masukkan nama lengkap"
                                required>

                            @error('nama_lengkap')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- NISN --}}
                        <div class="col-span-12 md:col-span-3">

                            <label class="form-label font-medium">
                                NISN
                            </label>

                            <input type="text"
                                name="nisn"
                                class="form-control rounded-xl
                                    @error('nisn') border-danger @enderror"
                                value="{{ old('nisn', $rows->nisn ?? '') }}"
                                placeholder="Masukkan NISN">

                            @error('nisn')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- NIK --}}
                        <div class="col-span-12 md:col-span-3">

                            <label class="form-label font-medium">
                                NIK
                            </label>

                            <input type="text"
                                name="nik"
                                class="form-control rounded-xl
                                    @error('nik') border-danger @enderror"
                                value="{{ old('nik', $rows->nik ?? '') }}"
                                placeholder="Masukkan NIK">

                            @error('nik')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- JENIS KELAMIN --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium block mb-2">
                                Jenis Kelamin
                            </label>

                            <div class="flex gap-4">

                                <label class="flex items-center gap-2">
                                    <input type="radio"
                                        name="jk"
                                        value="L"
                                        {{ old(
                                            'jk',
                                            $rows->jk ?? ''
                                        ) == 'L' ? 'checked' : '' }}>

                                    <span>Laki-Laki</span>
                                </label>

                                <label class="flex items-center gap-2">
                                    <input type="radio"
                                        name="jk"
                                        value="P"
                                        {{ old(
                                            'jk',
                                            $rows->jk ?? ''
                                        ) == 'P' ? 'checked' : '' }}>

                                    <span>Perempuan</span>
                                </label>

                            </div>

                            @error('jk')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- AGAMA --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Agama
                            </label>

                            <select name="id_agama"
                                class="form-select rounded-xl
                                    @error('id_agama') border-danger @enderror">

                                <option value="">
                                    -- Pilih Agama --
                                </option>

                                @foreach ($agama as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                            'id_agama',
                                            $rows->id_agama ?? ''
                                        ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_agama }}

                                </option>
                                @endforeach

                            </select>

                            @error('id_agama')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- TEMPAT LAHIR --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Tempat Lahir
                            </label>

                            <input type="text"
                                name="tmp_lahir"
                                class="form-control rounded-xl
                                    @error('tmp_lahir') border-danger @enderror"
                                value="{{ old(
                                    'tmp_lahir',
                                    $rows->tmp_lahir ?? ''
                                ) }}"
                                placeholder="Masukkan tempat lahir">

                            @error('tmp_lahir')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- TANGGAL LAHIR --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Tanggal Lahir
                            </label>

                            <input type="date"
                                name="tgl_lahir"
                                class="form-control rounded-xl"
                                value="{{ old('tgl_lahir', $rows->tgl_lahir ?? now()->format('Y-m-d')) }}">

                            @error('tgl_lahir')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- EMAIL --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Email
                            </label>

                            <input type="email"
                                name="email"
                                class="form-control rounded-xl
                                    @error('email') border-danger @enderror"
                                value="{{ old('email', $rows->email ?? '') }}"
                                placeholder="Masukkan email siswa">

                            @error('email')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- NO HP --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                No. HP
                            </label>

                            <input type="text"
                                name="no_hp"
                                class="form-control rounded-xl
                                    @error('no_hp') border-danger @enderror"
                                value="{{ old('no_hp', $rows->no_hp ?? '') }}"
                                placeholder="Contoh: 081234567890">

                            @error('no_hp')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- ALAMAT --}}
                        <div class="col-span-12">

                            <label class="form-label font-medium">
                                Alamat
                            </label>

                            <textarea name="alamat"
                                rows="3"
                                class="form-control rounded-xl resize-none
                                    @error('alamat') border-danger @enderror"
                                placeholder="Masukkan alamat lengkap">{{ old('alamat', $rows->alamat ?? '') }}</textarea>

                            @error('alamat')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                            @enderror

                        </div>

                        {{-- DUSUN --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-3">

                            <label class="form-label font-medium">
                                Dusun
                            </label>

                            <input type="text"
                                name="dusun"
                                class="form-control rounded-xl"
                                value="{{ old('dusun', $rows->dusun ?? '') }}">

                        </div>

                        {{-- DESA --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-3">

                            <label class="form-label font-medium">
                                Desa
                            </label>

                            <input type="text"
                                name="desa"
                                class="form-control rounded-xl"
                                value="{{ old('desa', $rows->desa ?? '') }}">

                        </div>

                        {{-- KECAMATAN --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-3">

                            <label class="form-label font-medium">
                                Kecamatan
                            </label>

                            <input type="text"
                                name="kecamatan"
                                class="form-control rounded-xl"
                                value="{{ old(
                                    'kecamatan',
                                    $rows->kecamatan ?? ''
                                ) }}">

                        </div>

                        {{-- KOTA --}}
                        <div class="col-span-12 md:col-span-6 lg:col-span-3">

                            <label class="form-label font-medium">
                                Kota/Kabupaten
                            </label>

                            <input type="text"
                                name="kota"
                                class="form-control rounded-xl"
                                value="{{ old('kota', $rows->kota ?? '') }}">

                        </div>

                        {{-- PROVINSI --}}
                        <div class="col-span-12 md:col-span-6">

                            <label class="form-label font-medium">
                                Provinsi
                            </label>

                            <input type="text"
                                name="provinsi"
                                class="form-control rounded-xl"
                                value="{{ old(
                                    'provinsi',
                                    $rows->provinsi ?? ''
                                ) }}">

                        </div>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="px-6 py-5 border-t bg-slate-50
                flex justify-end gap-3">

                <a href="{{ route('siswa.index') }}"
                    class="btn btn-outline-secondary rounded-xl">

                    Kembali
                </a>

                <button type="submit"
                    class="btn btn-primary rounded-xl px-8">

                    <i data-lucide="save"
                        class="w-4 h-4 mr-2"></i>

                    Simpan Data Siswa
                </button>

            </div>

        </div>

    </div>

</form>