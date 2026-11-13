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

    public function test_menambah_dosen(): void
    {
        $this->postJson('/api/dosen', ['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi'])
            ->assertCreated()
            ->assertJsonPath('message', 'Dosen berhasil ditambahkan');

        $this->assertDatabaseHas('dosen', ['nidn' => '0011']);
    }

    public function test_menambah_dosen_gagal_validasi(): void
    {
        Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);

        $this->postJson('/api/dosen', ['nidn' => '0011'])
            ->assertStatus(422)
            ->assertJsonPath('errors.nidn.0', 'NIDN sudah terdaftar.')
            ->assertJsonPath('errors.nama.0', 'Nama dosen wajib diisi.');
    }

    public function test_mengedit_dosen(): void
    {
        $dosen = Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);

        // NIDN sama dengan miliknya sendiri tetap boleh
        $this->putJson("/api/dosen/{$dosen->id}", ['nidn' => '0011', 'nama' => 'Budi Santoso', 'prodi' => 'Informatika'])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Budi Santoso');
    }

    public function test_mengedit_dosen_yang_tidak_ada(): void
    {
        $this->putJson('/api/dosen/9999', ['nidn' => '0011', 'nama' => 'X', 'prodi' => 'Y'])
            ->assertNotFound();
    }

    public function test_menghapus_dosen(): void
    {
        $dosen = Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);

        $this->deleteJson("/api/dosen/{$dosen->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Dosen berhasil dihapus');

        $this->assertDatabaseMissing('dosen', ['id' => $dosen->id]);
    }

    public function test_dosen_yang_mengampu_mata_kuliah_tidak_bisa_dihapus(): void
    {
        $dosen = Dosen::create(['nidn' => '0011', 'nama' => 'Budi', 'prodi' => 'Sistem Informasi']);
        $dosen->mataKuliah()->create(['kode' => 'IF101', 'nama' => 'Pemrograman Web', 'sks' => 3]);

        $this->deleteJson("/api/dosen/{$dosen->id}")
            ->assertStatus(409)
            ->assertJsonPath('success', false);

        $this->assertDatabaseHas('dosen', ['id' => $dosen->id]);
    }

    public function test_halaman_dosen_api_bisa_dibuka(): void
    {
        $this->get('/dosen-api')
            ->assertOk()
            ->assertSee('Dosen (REST API)');
    }
}
