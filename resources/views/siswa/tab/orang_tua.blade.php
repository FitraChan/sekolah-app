<form action="{{ route('siswa.update.orangtua', $rows->id ?? '') }}"
    method="POST">

    @csrf
    @method('PUT')

    <div class="box shadow-sm border border-slate-200 rounded-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b bg-slate-50">

            <div class="flex items-center">

                <div class="w-10 h-10 rounded-full bg-primary/10
                    flex items-center justify-center mr-3">

                    <i data-lucide="users"
                        class="w-5 h-5 text-primary"></i>

                </div>

                <div>
                    <h2 class="text-lg font-medium">
                        Data Orang Tua dan Wali
                    </h2>

                    <p class="text-slate-500 text-sm mt-1">
                        Lengkapi data ayah, ibu, dan wali siswa
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

            {{-- DATA AYAH --}}
<div class="px-6 py-5 border-b bg-slate-50">
                <h3 class="font-semibold text-base mb-4">
                    Data Ayah
                </h3>

                <div class="grid grid-cols-12 gap-5">

                    {{-- Nama Ayah --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Nama Ayah
                        </label>

                        <input type="text"
                            name="nama_ayah"
                            class="form-control rounded-xl
                                @error('nama_ayah') border-danger @enderror"
                            placeholder="Masukkan nama ayah"
                            value="{{ old('nama_ayah', $rows->nama_ayah ?? '') }}">

                        @error('nama_ayah')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Pekerjaan Ayah --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Pekerjaan Ayah
                        </label>

                        <select name="id_kerja_ayah"
                            class="form-select rounded-xl
                                @error('id_kerja_ayah') border-danger @enderror">

                            <option value="">
                                -- Pilih Pekerjaan Ayah --
                            </option>

                            @foreach ($jobs as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                        'id_kerja_ayah',
                                        $rows->id_kerja_ayah ?? ''
                                    ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_pekerjaan }}

                                </option>
                            @endforeach

                        </select>

                        @error('id_kerja_ayah')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Alamat Ayah --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Alamat Ayah
                        </label>

                        <textarea name="alamat_ayah"
                            rows="3"
                            class="form-control rounded-xl resize-none
                                @error('alamat_ayah') border-danger @enderror"
                            placeholder="Masukkan alamat ayah">{{ old('alamat_ayah', $rows->alamat_ayah ?? '') }}</textarea>

                        @error('alamat_ayah')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- HP Ayah --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            No. HP Ayah
                        </label>

                        <input type="text"
                            name="hp_ayah"
                            class="form-control rounded-xl
                                @error('hp_ayah') border-danger @enderror"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('hp_ayah', $rows->hp_ayah ?? '') }}">

                        @error('hp_ayah')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <hr class="my-6">

            {{-- DATA IBU --}}
           <div class="px-6 py-5 border-b bg-slate-50">

                <h3 class="font-semibold text-base mb-4">
                    Data Ibu
                </h3>

                <div class="grid grid-cols-12 gap-5">

                    {{-- Nama Ibu --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Nama Ibu
                        </label>

                        <input type="text"
                            name="nama_ibu"
                            class="form-control rounded-xl
                                @error('nama_ibu') border-danger @enderror"
                            placeholder="Masukkan nama ibu"
                            value="{{ old('nama_ibu', $rows->nama_ibu ?? '') }}">

                        @error('nama_ibu')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Pekerjaan Ibu --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Pekerjaan Ibu
                        </label>

                        <select name="id_kerja_ibu"
                            class="form-select rounded-xl
                                @error('id_kerja_ibu') border-danger @enderror">

                            <option value="">
                                -- Pilih Pekerjaan Ibu --
                            </option>

                            @foreach ($jobs as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                        'id_kerja_ibu',
                                        $rows->id_kerja_ibu ?? ''
                                    ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_pekerjaan }}

                                </option>
                            @endforeach

                        </select>

                        @error('id_kerja_ibu')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Alamat Ibu --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Alamat Ibu
                        </label>

                        <textarea name="alamat_ibu"
                            rows="3"
                            class="form-control rounded-xl resize-none
                                @error('alamat_ibu') border-danger @enderror"
                            placeholder="Masukkan alamat ibu">{{ old('alamat_ibu', $rows->alamat_ibu ?? '') }}</textarea>

                        @error('alamat_ibu')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- HP Ibu --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            No. HP Ibu
                        </label>

                        <input type="text"
                            name="hp_ibu"
                            class="form-control rounded-xl
                                @error('hp_ibu') border-danger @enderror"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('hp_ibu', $rows->hp_ibu ?? '') }}">

                        @error('hp_ibu')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

            <hr class="my-6">

            {{-- DATA WALI --}}
            <div class="px-6 py-5 border-b bg-slate-50">

                <h3 class="font-semibold text-base mb-4">
                    Data Wali
                </h3>

                <p class="text-slate-500 text-sm mb-4">
                    Bagian ini dapat dikosongkan jika siswa tidak memiliki wali.
                </p>

                <div class="grid grid-cols-12 gap-5">

                    {{-- Nama Wali --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Nama Wali
                        </label>

                        <input type="text"
                            name="nama_wali"
                            class="form-control rounded-xl
                                @error('nama_wali') border-danger @enderror"
                            placeholder="Masukkan nama wali"
                            value="{{ old('nama_wali', $rows->nama_wali ?? '') }}">

                        @error('nama_wali')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Pekerjaan Wali --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Pekerjaan Wali
                        </label>

                        <select name="id_kerja_wali"
                            class="form-select rounded-xl
                                @error('id_kerja_wali') border-danger @enderror">

                            <option value="">
                                -- Pilih Pekerjaan Wali --
                            </option>

                            @foreach ($jobs as $item)
                                <option value="{{ $item->id }}"
                                    {{ old(
                                        'id_kerja_wali',
                                        $rows->id_kerja_wali ?? ''
                                    ) == $item->id ? 'selected' : '' }}>

                                    {{ $item->nama_pekerjaan }}

                                </option>
                            @endforeach

                        </select>

                        @error('id_kerja_wali')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Alamat Wali --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Alamat Wali
                        </label>

                        <textarea name="alamat_wali"
                            rows="3"
                            class="form-control rounded-xl resize-none
                                @error('alamat_wali') border-danger @enderror"
                            placeholder="Masukkan alamat wali">{{ old('alamat_wali', $rows->alamat_wali ?? '') }}</textarea>

                        @error('alamat_wali')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- HP Wali --}}
                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            No. HP Wali
                        </label>

                        <input type="text"
                            name="hp_wali"
                            class="form-control rounded-xl
                                @error('hp_wali') border-danger @enderror"
                            placeholder="Contoh: 081234567890"
                            value="{{ old('hp_wali', $rows->hp_wali ?? '') }}">

                        @error('hp_wali')
                            <div class="text-danger text-sm mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="px-6 py-5 border-t bg-slate-50 flex justify-end gap-3">

            <a href="{{ route('siswa.index') }}"
                class="btn btn-outline-secondary rounded-xl">

                Kembali
            </a>

            <button type="submit"
                class="btn btn-primary rounded-xl px-8">

                <i data-lucide="save" class="w-4 h-4 mr-2"></i>

                Simpan Data Orang Tua
            </button>

        </div>

    </div>

</form>