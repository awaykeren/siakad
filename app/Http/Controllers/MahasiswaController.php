<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class MahasiswaController extends Controller
{
    public function index()
    {
        $sortOptions = [
            'nim' => 'NIM',
            'nama' => 'Nama',
            'prodi' => 'Program Studi',
        ];
        $sort = request('sort', 'nama');
        $direction = request('direction', 'asc');
        $sort = array_key_exists($sort, $sortOptions) ? $sort : 'nama';
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';

        $query = Mahasiswa::query();

        if ($search = trim((string) request('search', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nim', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('prodi', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nohp', 'like', "%{$search}%");
            });
        }

        if ($prodi = request('prodi')) {
            $query->where('prodi', $prodi);
        }

        $mahasiswa = $query
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();
        $programStudi = Mahasiswa::query()
            ->select('prodi')
            ->distinct()
            ->orderBy('prodi')
            ->pluck('prodi');

        return view('mahasiswa.index', compact(
            'mahasiswa',
            'programStudi',
            'sortOptions',
            'sort',
            'direction'
        ));
    }

    public function tampil()
    {
        // ambil data mahasiswa
        $mahasiswa = Mahasiswa::all();
        return view('tampilmahasiswa', compact('mahasiswa'));
    }
    public function data()
    {
        $mahasiswa = DB::table('mahasiswa')
            ->select(
                'id',
                'nim',
                'nama',
                'prodi',
                'email',
                'nohp'
            )
            ->orderBy('nama', 'asc')
            ->get();

        return view('tampilmahasiswa', compact('mahasiswa')
        );
    }

    public function detil($id)
    {
        $mahasiswa = Mahasiswa::find($id);
        return view('detilmahasiswa',compact('mahasiswa'));
    }
    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:mahasiswa,nim',
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'nohp' => 'nullable|string|max:20',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.max' => 'NIM maksimal 20 karakter.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 100 karakter.',
            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.max' => 'Program studi maksimal 100 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'nohp.max' => 'Nomor HP maksimal 20 karakter.',
        ]);

        Mahasiswa::create($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim' => [
                'required', 'string', 'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa->id),
            ],
            'nama' => 'required|string|max:100',
            'prodi' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'nohp' => 'nullable|string|max:20',
        ], [
            'nim.required' => 'NIM wajib diisi.',
            'nim.max' => 'NIM maksimal 20 karakter.',
            'nim.unique' => 'NIM sudah digunakan mahasiswa lain.',
            'nama.required' => 'Nama mahasiswa wajib diisi.',
            'nama.max' => 'Nama mahasiswa maksimal 100 karakter.',
            'prodi.required' => 'Program studi wajib diisi.',
            'prodi.max' => 'Program studi maksimal 100 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'nohp.max' => 'Nomor HP maksimal 20 karakter.',
        ]);

        $mahasiswa->update($validated);

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}
