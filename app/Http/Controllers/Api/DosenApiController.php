<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dosen;

/**
 * Contoh REST API sederhana untuk tabel dosen.
 * Bagian 1: MENAMPILKAN DATA (GET).
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
}
