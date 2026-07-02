<form action="{{ route('calon-siswa.update-status', $rows->id ?? 0) }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf

    <div class="box p-5">

        <h2 class="text-lg font-medium mb-5">
            Pembayaran Pendaftaran
        </h2>

        <div class="grid grid-cols-12 gap-6">

            <!-- Upload Bukti -->
            <div class="col-span-12">

                <label class="form-label">
                    Upload Bukti Transfer
                </label>

                <input
                    type="file"
                    name="bukti_transfer"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.pdf">

                <small class="text-slate-500">
                    Format yang diperbolehkan: JPG, JPEG, PNG, PDF (Maks. 2 MB)
                </small>

            </div>

            <!-- Preview File -->
            @if(!empty($rows->bukti_transfer))

            <div class="col-span-12">

                <label class="form-label">
                    Bukti Transfer Saat Ini
                </label>

                <div class="mt-2">

                    <a href="{{ asset('storage/'.$rows->bukti_transfer) }}"
                        target="_blank"
                        class="btn btn-outline-primary">

                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>

                        Lihat Bukti Transfer

                    </a>

                </div>

            </div>

            @endif

        </div>

       <div class="flex items-center mt-8 w-full">

    <button
        type="submit"
        class="btn btn-success">

        <i data-lucide="upload" class="w-4 h-4 mr-2"></i>
        Simpan Bukti Transfer

    </button>

    <a href="javascript:void(0)"
       class="btn btn-primary ml-auto"
       data-tw-toggle="modal"
       data-tw-target="#modal-add-kelas">

        <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
        Bayar via iPaymu

    </a>

</div>

    </div>

</form>

@include('pendaftaran.calon_siswa.tab.ipaymu-modal')