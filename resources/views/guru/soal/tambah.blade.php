 @extends('layout.main')

 @section('tittle')
 Soal
 @endsection

 @section('top-nav')
 <ol class="breadcrumb">
     <li class="breadcrumb-item">Daftar Soal</li>
 </ol>
 @endsection

 @section('body')
 <div class="w-full max-w-none px-6 py-6">

     <div class="intro-y box">

         <div class="flex items-center p-5 border-b border-slate-200/60">
             <h2 class="font-medium text-base">
                 Daftar Soal
             </h2>
         </div>

         <div class="p-6">

             <form id="formEditSoal" enctype="multipart/form-data">
                 @csrf
                 <div class="modal-body grid grid-cols-12 gap-4 gap-y-3">

                     <input type="hidden" id="form_mode" value="add">
                     <input type="hidden" name="id" id="e_id">


                     <!-- Judul -->
                     <div class="col-span-12">
                         <label class="form-label">Judul Soal</label>
                         <div class="input-group">
                             <div class="input-group-text">Judul</div>
                             <input
                                 type="text"
                                 class="form-control"
                                 id="e_judul_soal"
                                 name="judul_soal"
                                 placeholder="Masukkan Judul Soal">
                         </div>
                     </div>

                     <!-- Jenis Soal -->
                     <div class="col-span-6">
                         <label class="form-label">Jenis Soal</label>

                         <select
                             class="form-select"
                             id="e_jenis_soal_id"
                             name="jenis_soal_id">

                             <option value="">Pilih Jenis Soal</option>

                             @foreach($jenis_soal as $row)
                             <option value="{{ $row->id }}">
                                 {{ $row->jenis_soal }}
                             </option>
                             @endforeach

                         </select>
                     </div>

                     <!-- Mata Pelajaran -->
                     <div class="col-span-6">
                         <label class="form-label">Mata Pelajaran</label>

                         <select
                             class="form-select"
                             id="e_mapel_id"
                             name="mapel_id">

                             <option value="">Pilih Mata Pelajaran</option>

                             @foreach($mapel as $row)
                             <option value="{{ $row['id_mapel'] }}">
                                 {{ $row['nama_mapel'] }}
                                
                             </option>
                             @endforeach

                         </select>
                     </div>

                     <!-- Semester -->
                     <div class="col-span-6">
                         <label class="form-label">Semester</label>

                         <input
                             type="text"
                             class="form-control"
                             id="e_smt"
                             name="smt"
                             value="{{ $smt }}"
                             readonly>
                     </div>

                     <!-- Jawaban Benar -->
                     <div class="col-span-6">
                         <label class="form-label">Jawaban Benar</label>

                         <select
                             class="form-select"
                             id="e_jawaban_benar"
                             name="jawaban_benar">

                             <option value="">Pilih Jawaban</option>
                             <option value="A">A</option>
                             <option value="B">B</option>
                             <option value="C">C</option>
                             <option value="D">D</option>
                             <option value="E">E</option>

                         </select>
                     </div>

                     <!-- Soal -->
                     <div class="col-span-12">
                         <label class="form-label">Soal</label>

                         <textarea
                             id="e_editor"
                             name="soal"></textarea>
                     </div>

                    

                   
                     <!-- Jawaban -->
                     @foreach(['a','b','c','d','e'] as $jwb)

                     <div class="col-span-6">

                         <label class="form-label">
                             Jawaban {{ strtoupper($jwb) }}
                         </label>

                         <div class="input-group">

                             <div class="input-group-text">
                                 {{ strtoupper($jwb) }}
                             </div>

                             <input
                                 type="text"
                                 class="form-control"
                                 id="e_jawaban_{{ $jwb }}"
                                 name="jawaban_{{ $jwb }}"
                                 placeholder="Masukkan Jawaban {{ strtoupper($jwb) }}">

                         </div>

                     </div>

                     @endforeach

                 </div>

                 <div class="modal-footer">

                     <a href="{{ route('soalGuru.index') }}"
                         class="btn btn-outline-secondary">
                         Kembali
                     </a>

                     <button
                         type="submit"
                         class="btn btn-primary">
                         Simpan
                     </button>

                 </div>

             </form>

             

         </div>
     </div>

 </div>

 </div>
<script>

    CKEDITOR.replace('e_editor', {
    filebrowserBrowseUrl: "{{ asset('ckfinder/ckfinder.html') }}",
    filebrowserImageBrowseUrl: "{{ asset('ckfinder/ckfinder.html?type=Images') }}",
        filebrowserUploadUrl: "{{ route('ckeditor.upload') }}?_token={{ csrf_token() }}",
    filebrowserUploadMethod: 'form',
        removePlugins: 'easyimage,cloudservices'

});

</script>
 @endsection