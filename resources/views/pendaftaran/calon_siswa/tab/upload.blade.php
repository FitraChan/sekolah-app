<form action="{{ route('calon-siswa.update.upload', $rows->id ?? 0) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <div class="box p-5 mt-5">

        <h2 class="text-lg font-medium mb-5">
            Upload Dokumen
        </h2>

        <div class="grid grid-cols-12 gap-4">

            <!-- FOTO SISWA -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    Foto Siswa
                </label>

                <input type="file"
                       class="form-control"
                       name="foto_siswa"
                       accept="image/*">

                @if(!empty($rows->foto_siswa))

                    <div class="mt-3">

                        <img src="{{ asset('storage/app/public/' . $rows->foto_siswa) }}"
                             class="w-40 rounded-lg border shadow">

                    </div>

                @endif

            </div>

            <!-- KK -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    Kartu Keluarga (KK)
                </label>

                <input type="file"
                       class="form-control"
                       name="kk"
                       accept=".pdf,image/*">

                @if(!empty($rows->kk))

                    <div class="mt-3">

                        @php
                            $ext = pathinfo($rows->kk, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))

                            <img src="{{ asset('storage/app/public/' . $rows->kk) }}"
                                 class="w-40 rounded-lg border shadow">

                        @else

                            <a href="{{ asset('storage/app/public/' . $rows->kk) }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                Lihat KK

                            </a>

                        @endif

                    </div>

                @endif

            </div>

            <!-- AKTA -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    Akta Kelahiran
                </label>

                <input type="file"
                       class="form-control"
                       name="akta_kelahiran"
                       accept=".pdf,image/*">

                @if(!empty($rows->akta_kelahiran))

                    <div class="mt-3">

                        @php
                            $ext = pathinfo($rows->akta_kelahiran, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))

                            <img src="{{ asset('storage/app/public/' . $rows->akta_kelahiran) }}"
                                 class="w-40 rounded-lg border shadow">

                        @else

                            <a href="{{ asset('storage/app/public/' . $rows->akta_kelahiran) }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                Lihat Akta

                            </a>

                        @endif

                    </div>

                @endif

            </div>

            <!-- IJAZAH -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    Ijazah
                </label>

                <input type="file"
                       class="form-control"
                       name="ijazah"
                       accept=".pdf">

                @if(!empty($rows->ijazah))

                    <div class="mt-3">

                        <a href="{{ asset('storage/app/public/' . $rows->ijazah) }}"
                           target="_blank"
                           class="btn btn-outline-primary">

                            Lihat Ijazah

                        </a>

                    </div>

                @endif

            </div>

            <!-- RAPORT -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    Raport
                </label>

                <input type="file"
                       class="form-control"
                       name="raport"
                       accept=".pdf">

                @if(!empty($rows->raport))

                    <div class="mt-3">

                        <a href="{{ asset('storage/app/public/' . $rows->raport) }}"
                           target="_blank"
                           class="btn btn-outline-primary">

                            Lihat Raport

                        </a>

                    </div>

                @endif

            </div>

            <!-- KTP AYAH -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    KTP Ayah
                </label>

                <input type="file"
                       class="form-control"
                       name="ktp_ayah"
                       accept=".pdf,image/*">

                @if(!empty($rows->ktp_ayah))

                    <div class="mt-3">

                        @php
                            $ext = pathinfo($rows->ktp_ayah, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))

                            <img src="{{ asset('storage/app/public/' . $rows->ktp_ayah) }}"
                                 class="w-40 rounded-lg border shadow">

                        @else

                            <a href="{{ asset('storage/app/public/' . $rows->ktp_ayah) }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                Lihat KTP Ayah

                            </a>

                        @endif

                    </div>

                @endif

            </div>

            <!-- KTP IBU -->
            <div class="col-span-12 md:col-span-6">

                <label class="form-label">
                    KTP Ibu
                </label>

                <input type="file"
                       class="form-control"
                       name="ktp_ibu"
                       accept=".pdf,image/*">

                @if(!empty($rows->ktp_ibu))

                    <div class="mt-3">

                        @php
                            $ext = pathinfo($rows->ktp_ibu, PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($ext), ['jpg','jpeg','png']))

                            <img src="{{ asset('storage/app/public/' . $rows->ktp_ibu) }}"
                                 class="w-40 rounded-lg border shadow">

                        @else

                            <a href="{{ asset('storage/app/public/' . $rows->ktp_ibu) }}"
                               target="_blank"
                               class="btn btn-outline-primary">

                                Lihat KTP Ibu

                            </a>

                        @endif

                    </div>

                @endif

            </div>

        </div>

        <div class="mt-5">

            <button type="submit"
                    class="btn btn-primary rounded-xl px-8">

                Simpan

            </button>

        </div>

    </div>

</form>

