<?php

namespace Tests\Feature;

use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_menampilkan_semua_dosen(): void
    {
        Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);
        Dosen::create(['nidn' => '0022', 'nama' => 'Ani', 'prodi' => 'Teknik Informatika']);

        $this->getJson('/api/dosen')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.nama', 'Ani'); // urut berdasarkan nama
    }

    public function test_menampilkan_satu_dosen(): void
    {
        $dosen = Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);

        $this->getJson("/api/dosen/{$dosen->id}")
            ->assertOk()
            ->assertJsonPath('data.nidn', '0011');
    }

    public function test_dosen_tidak_ditemukan(): void
    {
        $this->getJson('/api/dosen/9999')
            ->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Dosen tidak ditemukan');
    }

    public function test_halaman_dosen_api_bisa_dibuka(): void
    {
        $this->get('/dosen-api')
            ->assertOk()
            ->assertSee('Dosen (REST API)');
    }
}
