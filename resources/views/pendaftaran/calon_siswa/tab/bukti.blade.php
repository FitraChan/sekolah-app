<form action="{{ route('calon-siswa.update-status', $rows->id ?? 0) }}"
      method="POST">

    @csrf
<div class="box p-5">

    <h2 class="text-lg font-medium mb-5">
        Bukti Pendaftaran
    </h2>

    <div class="grid grid-cols-12 gap-6">


       

        <!-- Status Siswa -->
        <div class="col-span-12">

            <label class="form-label">
                Status Siswa
            </label>

            <select name="status_daftar"
                    class="form-control">

                @foreach($sts_daftar as $baris)

                    <option value="{{ $baris['id'] }}"
                        {{ ($rows->status_daftar == $baris['id']) ? 'selected' : '' }}>

                        {{ $baris['keterangan'] }}

                    </option>

                @endforeach

            </select>

            

        </div>

        <button type="submit"
                        class="btn btn-primary rounded-xl px-8">

                    Simpan

                </button>

    </div>

</div>

</form>