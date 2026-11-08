<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    /**
     * GET /api/mahasiswa
     * Query string opsional: search, prodi, sort, direction, per_page, page
     */
    public function index(Request $request)
    {
        $sort = in_array($request->query('sort'), ['nim', 'nama', 'prodi'], true)
            ? $request->query('sort')
            : 'nama';
        $direction = $request->query('direction') === 'desc' ? 'desc' : 'asc';
        $perPage = min(max((int) $request->query('per_page', 10), 1), 100);

        $mahasiswa = Mahasiswa::query()
            ->when(trim((string) $request->query('search')), function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nim', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('prodi', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->query('prodi'), fn ($query, $prodi) => $query->where('prodi', $prodi))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        // Hasil: { data: [...], links: {...}, meta: {...} }
        return MahasiswaResource::collection($mahasiswa);
    }

    /**
     * POST /api/mahasiswa
     * Validasi otomatis oleh MahasiswaRequest; jika gagal -> 422.
     */
    public function store(MahasiswaRequest $request): JsonResponse
    {
        $mahasiswa = Mahasiswa::create($request->validated());

        return (new MahasiswaResource($mahasiswa))
            ->additional(['message' => 'Data mahasiswa berhasil ditambahkan.'])
            ->response()
            ->setStatusCode(201); // 201 Created
    }

    /**
     * GET /api/mahasiswa/{id}
     * Jika id tidak ada, route model binding otomatis -> 404.
     */
    public function show(Mahasiswa $mahasiswa): MahasiswaResource
    {
        return new MahasiswaResource($mahasiswa);
    }

    /**
     * PUT/PATCH /api/mahasiswa/{id}
     */
    public function update(MahasiswaRequest $request, Mahasiswa $mahasiswa): MahasiswaResource
    {
        $mahasiswa->update($request->validated());

        return (new MahasiswaResource($mahasiswa))
            ->additional(['message' => 'Data mahasiswa berhasil diperbarui.']);
    }

    /**
     * DELETE /api/mahasiswa/{id}
     */
    public function destroy(Mahasiswa $mahasiswa): JsonResponse
    {
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Data mahasiswa berhasil dihapus.',
        ]);
    }
}
