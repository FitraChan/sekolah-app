<form action="{{ route('siswa.update.upload', $rows->id ?? '') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="box shadow-sm border border-slate-200 rounded-2xl overflow-hidden mt-5">

        {{-- HEADER --}}
        <div class="px-6 py-5 border-b bg-slate-50">

            <div class="flex items-center">

                <div class="w-10 h-10 rounded-full bg-primary/10
                    flex items-center justify-center mr-3">

                    <i data-lucide="upload-cloud"
                        class="w-5 h-5 text-primary"></i>

                </div>

                <div>
                    <h2 class="text-lg font-medium">
                        Upload Dokumen Siswa
                    </h2>

                    <p class="text-slate-500 text-sm mt-1">
                        Unggah foto dan dokumen kelengkapan siswa
                    </p>
                </div>

            </div>

        </div>

        {{-- BODY --}}
        <div class="p-6">

            @if ($errors->any())
                <div class="alert alert-danger mb-5">

                    <div class="font-medium mb-2">
                        Dokumen belum dapat diunggah:
                    </div>

                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif
<div class="px-6 py-5 border-b bg-slate-50">
            <div class="grid grid-cols-12 gap-5">

                {{-- FOTO SISWA --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        Foto Siswa
                    </label>

                    <input type="file"
                        name="foto_siswa"
                        class="form-control rounded-xl
                            @error('foto_siswa') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,image/jpeg,image/png">

                    <div class="text-xs text-slate-500 mt-1">
                        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                    </div>

                    @error('foto_siswa')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->foto_siswa))
                        <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->foto_siswa) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

                </div>

                {{-- KARTU KELUARGA --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        Kartu Keluarga
                    </label>

                    <input type="file"
                        name="kk"
                        class="form-control rounded-xl
                            @error('kk') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format gambar atau PDF. Maksimal 2 MB.
                    </div>

                    @error('kk')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->kk))
                        <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->kk) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

                </div>

                {{-- AKTA KELAHIRAN --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        Akta Kelahiran
                    </label>

                    <input type="file"
                        name="akta_kelahiran"
                        class="form-control rounded-xl
                            @error('akta_kelahiran') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format gambar atau PDF. Maksimal 2 MB.
                    </div>

                    @error('akta_kelahiran')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->akta_kelahiran))
                       <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->akta_kelahiran) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

                </div>

                {{-- IJAZAH --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        Ijazah
                    </label>

                    <input type="file"
                        name="ijazah"
                        class="form-control rounded-xl
                            @error('ijazah') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format gambar atau PDF. Maksimal 4 MB.
                    </div>

                    @error('ijazah')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->ijazah))
                        <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->ijazah) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

                </div>

                {{-- RAPORT --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        Raport
                    </label>

                    <input type="file"
                        name="raport"
                        class="form-control rounded-xl
                            @error('raport') border-danger @enderror"
                        accept=".pdf,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format PDF. Maksimal 4 MB.
                    </div>

                    @error('raport')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->raport))
                        <div class="mt-3">

                            <a href="{{ asset('storage/app/public/' . $rows->raport) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn btn-outline-primary">

                                <i data-lucide="file-text"
                                    class="w-4 h-4 mr-2"></i>

                                Lihat Raport
                            </a>

                        </div>
                    @endif

                </div>

                {{-- KTP AYAH --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        KTP Ayah
                    </label>

                    <input type="file"
                        name="ktp_ayah"
                        class="form-control rounded-xl
                            @error('ktp_ayah') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format gambar atau PDF. Maksimal 2 MB.
                    </div>

                    @error('ktp_ayah')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                    @if (!empty($rows->ktp_ayah))
                        <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->ktp_ayah) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

                </div>

                {{-- KTP IBU --}}
                <div class="col-span-12 md:col-span-6">

                    <label class="form-label font-medium">
                        KTP Ibu
                    </label>

                    <input type="file"
                        name="ktp_ibu"
                        class="form-control rounded-xl
                            @error('ktp_ibu') border-danger @enderror"
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf">

                    <div class="text-xs text-slate-500 mt-1">
                        Format gambar atau PDF. Maksimal 2 MB.
                    </div>

                    @error('ktp_ibu')
                        <div class="text-danger text-sm mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                    @if (!empty($rows->ktp_ibu))

                        <div class="mt-3">

                            <img src="{{ asset('storage/app/public/' . $rows->ktp_ibu) }}"
                                alt="Foto siswa"
                                class="w-40 h-40 object-cover rounded-lg border shadow-sm">

                        </div>
                    @endif

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

                <i data-lucide="upload"
                    class="w-4 h-4 mr-2"></i>

                Upload Dokumen
            </button>

        </div>

    </div>

</form>