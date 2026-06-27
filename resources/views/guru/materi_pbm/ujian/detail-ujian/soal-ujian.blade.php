<div class="intro-y box mt-5">
    <div class="p-5">

        @foreach($soals as $index => $soal)

            <div class="border rounded-md p-5 mb-5">

<div class="flex items-center">
                    <h5 class="font-medium text-base">
                        No. {{ $index + 1 }}
                    </h5>

    <div class="ml-auto flex gap-2">
                        <a href="#"
                           class="btn btn-sm btn-outline-primary btn-edit"
                           data-id="{{ $soal['soal_id'] }}"
                           data-tw-toggle="modal"
                           data-tw-target="#md-edit">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </a>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-danger btn-hapus"
                            data-id="{{ $soal['id'] }}">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-success btn-update-up"
                            data-id="{{ $soal['id'] }}">
                            <i data-lucide="arrow-up" class="w-4 h-4"></i>
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-success btn-update-down"
                            data-id="{{ $soal['id'] }}">
                            <i data-lucide="arrow-down" class="w-4 h-4"></i>
                        </button>

                    </div>

                </div>

                <div class="mb-3">
                    {!! $soal['soal'] !!}
                </div>

                @if($soal['jenis_soal_id'] == 1)

<ol style="list-style-type: upper-alpha; padding-left:25px;">
                        <li>{!! $soal['jawaban_a'] !!}</li>
                        <li>{!! $soal['jawaban_b'] !!}</li>
                        <li>{!! $soal['jawaban_c'] !!}</li>
                        <li>{!! $soal['jawaban_d'] !!}</li>

                        @if(!empty($soal['jawaban_e']))
                            <li>{!! $soal['jawaban_e'] !!}</li>
                        @endif

                    </ol>

                @endif

                <div class="mt-4">
                    <span class="font-medium text-success">
                        Jawaban Benar :
                        {{ $soal['jawaban_benar'] }}
                    </span>
                </div>

            </div>

        @endforeach

    </div>
</div>