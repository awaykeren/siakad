<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
{
    /**
     * Semua client boleh mengirim request ini.
     * (Pembatasan akses diatur lewat middleware, misalnya Sanctum.)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi dipakai bersama oleh store (POST) dan update (PUT).
     */
    public function rules(): array
    {
        // Saat update, route berisi model mahasiswa yang sedang diubah.
        // NIM miliknya sendiri tidak boleh dianggap "sudah terdaftar".
        $mahasiswa = $this->route('mahasiswa');

        return [
            'nim' => [
                'required', 'string', 'max:20',
                Rule::unique('mahasiswa', 'nim')->ignore($mahasiswa?->id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'prodi' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:100'],
            'nohp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
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
        ];
    }
}
