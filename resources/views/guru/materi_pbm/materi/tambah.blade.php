

<div class="max-w-7xl mx-auto p-6">

    <div class="intro-y box">

        <div class="p-5 border-b">

            <h2 class="text-xl font-semibold">
                Mata Pelajaran {{ $master->mapel->nama_mapel }}
            </h2>

            <p class="text-slate-500 mt-1">
                Kelas {{ $master->kelas->kelas }}
                {{ $master->kelas->nama_kelas }}
            </p>

        </div>

        <div class="p-5">

            <form id="form-materi" enctype="multipart/form-data">

                @csrf

                <input
                    type="hidden"
                    name="idjadwal"
                    value="{{ $id }}">

                <div class="grid grid-cols-12 gap-4">

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            Pertemuan Ke
                        </label>

                        <input
                            type="number"
                            name="idpertemuan"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            Judul Materi
                        </label>

                        <input
                            type="text"
                            name="judul_materi"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="tgl"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            Guru Pengganti
                        </label>

                        <select
                            name="guru_pengganti"
                            class="form-select">

                            <option value="">
                                Pilih Guru
                            </option>

                            @foreach($guru as $row)
                                <option value="{{ $row->id }}">
                                    {{ $row->nama_gtk }}
                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            URL Video
                        </label>

                        <input
                            type="text"
                            name="url_video"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-6">
                        <label class="form-label">
                            Jenis Video
                        </label>

                        <select
                            name="is_youtube"
                            class="form-select">

                            <option value="1">
                                Video Youtube
                            </option>

                            <option value="0">
                                File Video
                            </option>

                        </select>

                    </div>

                </div>

                <div class="mt-5">

                    <label class="form-label">
                        Materi
                    </label>

                    <textarea
                        name="materi"
                        rows="10"
                        class="form-control"></textarea>

                </div>

                <div class="grid grid-cols-12 gap-4 mt-5">

                    <div class="col-span-12 md:col-span-4">
                        <label class="form-label">
                            Lampiran 1
                        </label>

                        <input
                            type="file"
                            name="url_materi_1"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label class="form-label">
                            Lampiran 2
                        </label>

                        <input
                            type="file"
                            name="url_materi_2"
                            class="form-control">
                    </div>

                    <div class="col-span-12 md:col-span-4">
                        <label class="form-label">
                            Lampiran 3
                        </label>

                        <input
                            type="file"
                            name="url_materi_3"
                            class="form-control">
                    </div>

                </div>

                <hr class="my-6">

                <h3 class="font-semibold text-lg mb-4">
                    Data Tugas
                </h3>

                <div class="grid grid-cols-12 gap-4">

                    <div class="col-span-12 md:col-span-6">

                        <label class="form-label">
                            Judul Tugas
                        </label>

                        <input
                            type="text"
                            name="judul_tugas"
                            class="form-control">

                    </div>

                    <div class="col-span-12 md:col-span-3">

                        <label class="form-label">
                            Batas Submit
                        </label>

                        <input
                            type="datetime-local"
                            name="tgl_batas_submit"
                            class="form-control">

                    </div>

                    <div class="col-span-12 md:col-span-3">

                        <label class="form-label">
                            Lampiran Tugas
                        </label>

                        <input
                            type="file"
                            name="url_tugas"
                            class="form-control">

                    </div>

                </div>

                <div class="mt-5">

                    <label class="form-label">
                        Tugas
                    </label>

                    <textarea
                        name="tugas"
                        rows="8"
                        class="form-control"></textarea>

                </div>

                <div class="mt-5">

                    <label class="form-label">
                        Keterangan
                    </label>

                    <textarea
                        name="keterangan"
                        rows="4"
                        class="form-control"></textarea>

                </div>

                <div class="mt-6">

                    <button
                        type="submit"
                        class="btn btn-success w-full">

                        Simpan Materi

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

