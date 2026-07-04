<form action="{{ route('calon-siswa.update-status', $rows->id ?? 0) }}"
    method="POST">

    @csrf
    <div class="box p-5">

        <h2 class="text-lg font-medium mb-5">
            Bukti Pendaftaran
        </h2>

        <div class="grid grid-cols-12 gap-6">

            <div class="col-span-12">

                <label class="form-label">
                    Bukti Transfer
                </label>

                @foreach($bukti as $calon)

                @if($calon->buktiPembayaran->count())

                @foreach($calon->buktiPembayaran as $row)

                <div class="mt-3">

                    <a href="{{ asset('storage/app/public/'.$row->bukti_transfer) }}"
                        target="_blank">

                        <img src="{{ asset('storage/app/public/'.$row->bukti_transfer) }}"
                            class="w-72 rounded-lg border shadow"
                            alt="Bukti Transfer">

                    </a>

                    <p class="text-xs text-slate-500 mt-2">
                        Klik gambar untuk memperbesar.
                    </p>

                </div>

                @endforeach

                @else

                <div class="alert alert-warning">
                    Belum ada bukti pembayaran.
                </div>

                @endif

                @endforeach

            </div>


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

<br/>
<div class="box p-5">

    <h2 class="text-lg font-medium mb-5">
        Bukti Pembayaran iPaymu
    </h2>

    <div class="grid grid-cols-12 gap-6">

        @if($dataIpaymu)

            @php
                $totalBayar = $dataIpaymu->detailBayar->sum('jml_bayar');

                $status = [
                    1 => ['Lunas', 'success'],
                    2 => ['Pending', 'warning'],
                    3 => ['Gagal', 'danger'],
                ];

                $via = [
                    1 => 'Cash',
                    2 => 'Transfer',
                    3 => 'QRIS',
                    4 => 'iPaymu',
                ];
            @endphp

            <div class="col-span-6">
                <label class="font-medium">No. Kwitansi</label>
                <div>{{ $dataIpaymu->no_kwitansi ?? '-' }}</div>
            </div>

            <div class="col-span-6">
                <label class="font-medium">Tanggal Bayar</label>
                <div>
                    {{ optional($dataIpaymu->tgl_bayar)->format('d-m-Y H:i') ?? $dataIpaymu->tgl_bayar }}
                </div>
            </div>

            <div class="col-span-6">
                <label class="font-medium">Nama Siswa</label>
                <div>{{ $dataIpaymu->calonSiswa?->nama_lengkap ?? '-' }}</div>
            </div>

            <div class="col-span-6">
                <label class="font-medium">Total Bayar</label>
                <div>
                    <strong>
                        Rp {{ number_format($totalBayar, 0, ',', '.') }}
                    </strong>
                </div>
            </div>

            <div class="col-span-6">
                <label class="font-medium">Via Pembayaran</label>
                <div>{{ $via[$dataIpaymu->via] ?? '-' }}</div>
            </div>

            <div class="col-span-6">
                <label class="font-medium">Status</label>

                @php
                    $badge = $status[$dataIpaymu->sts_bayar] ?? ['Tidak Diketahui', 'secondary'];
                @endphp

                <span class="badge bg-{{ $badge[1] }}">
                    {{ $badge[0] }}
                </span>
            </div>

            @if(!empty($dataIpaymu->keterangan))
                <div class="col-span-12">
                    <label class="font-medium">Keterangan</label>
                    <div>{{ $dataIpaymu->keterangan }}</div>
                </div>
            @endif

            <div class="col-span-12 mt-4">

                <h3 class="font-medium mb-3">
                    Detail Pembayaran
                </h3>

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>Item Pembayaran</th>
                            <th class="text-end">Kewajiban</th>
                            <th class="text-end">Potongan</th>
                            <th class="text-end">Dibayar</th>
                            <th class="text-end">Sisa</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($dataIpaymu->detailBayar as $detail)

                            <tr>
                                <td>{{ $detail->nama_item }}</td>

                                <td class="text-end">
                                    Rp {{ number_format($detail->kwajiban_bayar,0,',','.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($detail->potongan,0,',','.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($detail->jml_bayar,0,',','.') }}
                                </td>

                                <td class="text-end">
                                    Rp {{ number_format($detail->sisa_bayar,0,',','.') }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="text-center">
                                    Tidak ada detail pembayaran.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        @else

            <div class="col-span-12">

                <div class="alert alert-warning">
                    Belum ada transaksi iPaymu.
                </div>

            </div>

        @endif

    </div>

</div>      