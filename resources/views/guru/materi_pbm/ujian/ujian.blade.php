 <div class="intro-y col-span-12 overflow-auto lg:overflow-visible">
                        <table class="table table-report -mt-2">
                            <thead>
                                <tr>
                                    <th class="whitespace-nowrap">IMAGES</th>
                                    <th class="whitespace-nowrap">JUDUL</th>
                                    <th class="text-center whitespace-nowrap">TANGGAL QUIZ</th>
                                    <th class="text-center whitespace-nowrap">TANGGAL MULAI</th>
                                    <th class="text-center whitespace-nowrap">TANGGAL SELESAI</th>
                                    <th class="text-center whitespace-nowrap">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>

                                    @foreach($ujian as $row)

                                <tr class="intro-x">
                                    <td class="w-40">
                                        <div class="flex">
                                            <div class="w-10 h-10 image-fit zoom-in">
                                                <img alt="#" class="tooltip rounded-full" src="dist/images/preview-10.jpg" title="Uploaded at 9 April 2022">
                                            </div>
                                            <div class="w-10 h-10 image-fit zoom-in -ml-5">
                                                <img alt="#" class="tooltip rounded-full" src="dist/images/preview-1.jpg" title="Uploaded at 9 April 2022">
                                            </div>
                                            <div class="w-10 h-10 image-fit zoom-in -ml-5">
                                                <img alt="#" class="tooltip rounded-full" src="dist/images/preview-6.jpg" title="Uploaded at 9 April 2022">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="" class="font-medium whitespace-nowrap">{{ $row->judul }}</a> 
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Ujian</div>
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tgl_quiz)->translatedFormat('d F Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tgl_mulai)->format('d-m-Y H:i') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($row->tgl_selesai)->format('d-m-Y H:i') }}</td>

                                    
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                        </div>
                                    </td>
                                </tr>
                                        @endforeach

                                <!-- <tr class="intro-x">
                                    <td class="w-40">
                                        <div class="flex">
                                            <div class="w-10 h-10 image-fit zoom-in">
                                                <img alt="Midone - HTML Admin Template" class="tooltip rounded-full" src="dist/images/preview-9.jpg" title="Uploaded at 2 September 2020">
                                            </div>
                                            <div class="w-10 h-10 image-fit zoom-in -ml-5">
                                                <img alt="Midone - HTML Admin Template" class="tooltip rounded-full" src="dist/images/preview-5.jpg" title="Uploaded at 2 September 2020">
                                            </div>
                                            <div class="w-10 h-10 image-fit zoom-in -ml-5">
                                                <img alt="Midone - HTML Admin Template" class="tooltip rounded-full" src="dist/images/preview-10.jpg" title="Uploaded at 2 September 2020">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="" class="font-medium whitespace-nowrap">Nikon Z6</a> 
                                        <div class="text-slate-500 text-xs whitespace-nowrap mt-0.5">Photography</div>
                                    </td>
                                    <td class="text-center">116</td>
                                    <td class="text-center">$52</td>
                                    <td class="w-40">
                                        <div class="flex items-center justify-center text-danger"> <i data-lucide="check-square" class="w-4 h-4 mr-2"></i> Inactive </div>
                                    </td>
                                    <td class="table-report__action w-56">
                                        <div class="flex justify-center items-center">
                                            <a class="flex items-center mr-3" href="javascript:;"> <i data-lucide="check-square" class="w-4 h-4 mr-1"></i> Edit </a>
                                            <a class="flex items-center text-danger" href="javascript:;" data-tw-toggle="modal" data-tw-target="#delete-confirmation-modal"> <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i> Delete </a>
                                        </div>
                                    </td>
                                </tr> -->
                                
                            </tbody>
                        </table>
                    </div>