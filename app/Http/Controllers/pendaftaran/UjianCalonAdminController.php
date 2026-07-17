<?php

namespace App\Http\Controllers\pendaftaran;

use App\Http\Controllers\Controller;
use App\Models\UjianCalon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UjianCalonAdminController extends Controller
{
    public function index(): View
    {  $side = 'ujian-calon-siswa';
        $data = UjianCalon::query()
            ->withCount(['soal', 'peserta'])
            ->latest()
            ->paginate(10);

     
        // return view('pendaftaran.calon_siswa.index', compact(
        //     'side',
        //     'gelombang',
        //     'jurusan'
        // ), ['side'  => 'calon-siswa']);

        return view('pendaftaran.ujian.index', compact('data','side'), ['side'  => 'ujian-calon-siswa']);
    }

    public function create(): View
    {
        return view('pendaftaran.ujian.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_ujian' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'durasi' => ['required', 'integer', 'min:1'],
            'nilai_minimal' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $validated['acak_soal'] = $request->boolean('acak_soal');
        $validated['tampil_hasil'] = $request->boolean('tampil_hasil');
        $validated['status'] = $request->boolean('status');

        UjianCalon::create($validated);

        return redirect()
            ->route('ujianCalonAdmin.index')
            ->with('success', 'Ujian berhasil dibuat.');
    }

    public function edit(UjianCalon $ujian): View
    {
        return view('admin.ujian.edit', compact('ujian'));
    }

    public function update(
        Request $request,
        UjianCalon $ujian
    ): RedirectResponse {
        $validated = $request->validate([
            'nama_ujian' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'durasi' => ['required', 'integer', 'min:1'],
            'nilai_minimal' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $validated['acak_soal'] = $request->boolean('acak_soal');
        $validated['tampil_hasil'] = $request->boolean('tampil_hasil');
        $validated['status'] = $request->boolean('status');

        $ujian->update($validated);

        return redirect()
            ->route('admin.ujian.index')
            ->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(UjianCalon $ujian): RedirectResponse
    {
        $ujian->delete();

        return redirect()
            ->route('admin.ujian.index')
            ->with('success', 'Ujian berhasil dihapus.');
    }
}