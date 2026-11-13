<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Contoh REST API sederhana untuk tabel dosen.
 * Bagian 1: menampilkan data (GET)
 * Bagian 2: tambah (POST), edit (PUT), hapus (DELETE)
 */
class DosenApiController extends Controller
{
    /**
     * GET /api/dosen
     * Mengambil semua data dosen.
     */
    public function index()
    {
        $dosen = Dosen::orderBy('nama')->get(['id', 'nidn', 'nama', 'prodi']);

        return response()->json([
            'success' => true,
            'message' => 'Daftar data dosen',
            'data' => $dosen,
        ], 200);
    }

    /**
     * GET /api/dosen/{id}
     * Mengambil satu data dosen berdasarkan id.
     */
    public function show($id)
    {
        $dosen = Dosen::find($id, ['id', 'nidn', 'nama', 'prodi']);

        // Jika data tidak ada, kirim status 404
        if (! $dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail data dosen',
            'data' => $dosen,
        ], 200);
    }

    /**
     * POST /api/dosen
     * Menambah dosen baru.
     */
    public function store(Request $request)
    {
        // Jika validasi gagal, Laravel otomatis membalas 422 + daftar error
        $validated = $request->validate([
            'nidn' => 'required|string|max:20|unique:dosen,nidn',
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
        ], $this->pesanValidasi());

        $dosen = Dosen::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dosen berhasil ditambahkan',
            'data' => $dosen->only(['id', 'nidn', 'nama', 'prodi']),
        ], 201); // 201 = Created
    }

    /**
     * PUT /api/dosen/{id}
     * Mengubah data dosen.
     */
    public function update(Request $request, $id)
    {
        $dosen = Dosen::find($id);

        if (! $dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $validated = $request->validate([
            // NIDN milik dosen ini sendiri tidak dianggap duplikat
            'nidn' => ['required', 'string', 'max:20', Rule::unique('dosen', 'nidn')->ignore($dosen->id)],
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
        ], $this->pesanValidasi());

        $dosen->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dosen berhasil diperbarui',
            'data' => $dosen->only(['id', 'nidn', 'nama', 'prodi']),
        ], 200);
    }

    /**
     * DELETE /api/dosen/{id}
     * Menghapus dosen.
     */
    public function destroy($id)
    {
        $dosen = Dosen::find($id);

        if (! $dosen) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak ditemukan',
                'data' => null,
            ], 404);
        }

        // Dosen yang masih mengampu mata kuliah tidak boleh dihapus
        // (tabel mata_kuliah memakai foreign key restrictOnDelete)
        if ($dosen->mataKuliah()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen tidak bisa dihapus karena masih mengampu mata kuliah',
                'data' => null,
            ], 409); // 409 = Conflict
        }

        $dosen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dosen berhasil dihapus',
            'data' => null,
        ], 200);
    }

    /**
     * Pesan error validasi dalam bahasa Indonesia (dipakai store & update).
     */
    private function pesanValidasi(): array
    {
        return [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.max' => 'NIDN maksimal 20 karakter.',
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.max' => 'Nama dosen maksimal 100 karakter.',
            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.max' => 'Program studi maksimal 100 karakter.',
        ];
    }
}
