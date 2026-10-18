<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    // READ: menampilkan daftar dosen
    public function index()
    {
        $sortOptions = [
            'nidn' => 'NIDN',
            'nama' => 'Nama',
            'prodi' => 'Program Studi',
        ];
        $sort = request('sort', 'nama');
        $direction = request('direction', 'asc');
        $sort = array_key_exists($sort, $sortOptions) ? $sort : 'nama';
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';

        $query = Dosen::with('mataKuliah');

        if ($search = trim((string) request('search', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nidn', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('prodi', 'like', "%{$search}%");
            });
        }

        if ($prodi = request('prodi')) {
            $query->where('prodi', $prodi);
        }

        $dosen = $query
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();
            
        $programStudi = Dosen::query()
            ->select('prodi')
            ->distinct()
            ->orderBy('prodi')
            ->pluck('prodi');

        return view('dosen.index', compact(
            'dosen',
            'programStudi',
            'sortOptions',
            'sort',
            'direction'
        ));
    }

    // Menampilkan form tambah
    public function create()
    {
        return view('dosen.create');
    }

    // CREATE: menyimpan dosen baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nidn' => 'required|string|max:20|unique:dosen,nidn',
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
        ], [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.string'   => 'NIDN harus berupa teks.',
            'nidn.max'      => 'NIDN maksimal 20 karakter.',
            'nidn.unique'   => 'NIDN sudah terdaftar, silakan gunakan NIDN lain.',

            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.string'   => 'Nama dosen harus berupa teks.',
            'nama.max'      => 'Nama dosen maksimal 100 karakter.',

            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.string'   => 'Program studi harus berupa teks.',
            'prodi.max'      => 'Program studi maksimal 100 karakter.',
        ]);

        Dosen::create($validated);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil ditambahkan.');
    }

    // Menampilkan form edit
    public function edit(Dosen $dosen)
    {
        return view('dosen.edit', compact('dosen'));
    }

    // UPDATE: memperbarui dosen
    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nidn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dosen', 'nidn')->ignore($dosen->id),
            ],
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
        ], [
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.string'   => 'NIDN harus berupa teks.',
            'nidn.max'      => 'NIDN maksimal 20 karakter.',
            'nidn.unique'   => 'NIDN sudah digunakan oleh dosen lain.',

            'nama.required' => 'Nama dosen wajib diisi.',
            'nama.string'   => 'Nama dosen harus berupa teks.',
            'nama.max'      => 'Nama dosen maksimal 100 karakter.',

            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.string'   => 'Program studi harus berupa teks.',
            'prodi.max'      => 'Program studi maksimal 100 karakter.',
        ]);

        $dosen->update($validated);

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    // DELETE: menghapus dosen
    public function destroy(Dosen $dosen)
    {
        $dosen->delete();

        return redirect()
            ->route('dosen.index')
            ->with('success', 'Data dosen berhasil dihapus.');
    }
}
