<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
{
    public function index()
    {
        $mataKuliah = MataKuliah::with('dosen')
            ->orderBy('kode')
            ->get();

        return view('mata-kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        $dosens = Dosen::orderBy('nama')->get();

        return view('mata-kuliah.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:255',
            'dosen_id' => 'required|exists:dosen,id',
        ], [
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'kode.max' => 'Kode mata kuliah maksimal 20 karakter.',
            'kode.unique' => 'Kode mata kuliah sudah terdaftar.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'nama.max' => 'Nama mata kuliah maksimal 100 karakter.',
            'sks.required' => 'Jumlah SKS wajib diisi.',
            'sks.integer' => 'Jumlah SKS harus berupa angka bulat.',
            'sks.min' => 'Jumlah SKS minimal 1.',
            'sks.max' => 'Jumlah SKS maksimal 255.',
            'dosen_id.required' => 'Dosen pengampu wajib dipilih.',
            'dosen_id.exists' => 'Dosen yang dipilih tidak ditemukan.',
        ]);

        MataKuliah::create($validated);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil ditambahkan.');
    }

    public function edit(MataKuliah $mataKuliah)
    {
        $dosens = Dosen::orderBy('nama')->get();

        return view('mata-kuliah.edit', compact('mataKuliah', 'dosens'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliah', 'kode')->ignore($mataKuliah->id),
            ],
            'nama' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:255',
            'dosen_id' => 'required|exists:dosen,id',
        ], [
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'kode.max' => 'Kode mata kuliah maksimal 20 karakter.',
            'kode.unique' => 'Kode mata kuliah sudah digunakan mata kuliah lain.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'nama.max' => 'Nama mata kuliah maksimal 100 karakter.',
            'sks.required' => 'Jumlah SKS wajib diisi.',
            'sks.integer' => 'Jumlah SKS harus berupa angka bulat.',
            'sks.min' => 'Jumlah SKS minimal 1.',
            'sks.max' => 'Jumlah SKS maksimal 255.',
            'dosen_id.required' => 'Dosen pengampu wajib dipilih.',
            'dosen_id.exists' => 'Dosen yang dipilih tidak ditemukan.',
        ]);

        $mataKuliah->update($validated);

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil diperbarui.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();

        return redirect()
            ->route('mata-kuliah.index')
            ->with('success', 'Data mata kuliah berhasil dihapus.');
    }
}
