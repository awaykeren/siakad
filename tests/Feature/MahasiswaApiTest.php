<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MahasiswaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_mengembalikan_daftar_terpaginasi(): void
    {
        Mahasiswa::factory()->count(15)->create();

        $this->getJson('/api/mahasiswa?per_page=10')
            ->assertOk()
            ->assertJsonCount(10, 'data')
            ->assertJsonPath('meta.total', 15)
            ->assertJsonStructure(['data' => [['id', 'nim', 'nama', 'prodi', 'email', 'nohp']], 'links', 'meta']);
    }

    public function test_index_bisa_mencari(): void
    {
        Mahasiswa::factory()->create(['nama' => 'Budi Santoso']);
        Mahasiswa::factory()->create(['nama' => 'Siti Aminah']);

        $this->getJson('/api/mahasiswa?search=Budi')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.nama', 'Budi Santoso');
    }

    public function test_store_menyimpan_data_dan_mengembalikan_201(): void
    {
        $payload = ['nim' => '2401001', 'nama' => 'Andi', 'prodi' => 'Sistem Informasi', 'email' => 'andi@example.com', 'nohp' => '0812'];

        $this->postJson('/api/mahasiswa', $payload)
            ->assertCreated()
            ->assertJsonPath('data.nim', '2401001')
            ->assertJsonPath('message', 'Data mahasiswa berhasil ditambahkan.');

        $this->assertDatabaseHas('mahasiswa', ['nim' => '2401001']);
    }

    public function test_store_validasi_gagal_mengembalikan_422(): void
    {
        Mahasiswa::factory()->create(['nim' => '2401001']);

        $this->postJson('/api/mahasiswa', ['nim' => '2401001', 'email' => 'bukan-email'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nim', 'nama', 'prodi', 'email']);
    }

    public function test_show_mengembalikan_satu_data(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->getJson("/api/mahasiswa/{$mhs->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $mhs->id);
    }

    public function test_show_id_tidak_ada_mengembalikan_404_json(): void
    {
        $this->get('/api/mahasiswa/9999')
            ->assertNotFound()
            ->assertJsonPath('message', 'Data tidak ditemukan.');
    }

    public function test_update_mengubah_data_dan_nim_sendiri_tidak_dianggap_duplikat(): void
    {
        $mhs = Mahasiswa::factory()->create(['nim' => '2401001']);

        $this->putJson("/api/mahasiswa/{$mhs->id}", ['nim' => '2401001', 'nama' => 'Nama Baru', 'prodi' => 'Teknik Informatika'])
            ->assertOk()
            ->assertJsonPath('data.nama', 'Nama Baru');
    }

    public function test_destroy_menghapus_data(): void
    {
        $mhs = Mahasiswa::factory()->create();

        $this->deleteJson("/api/mahasiswa/{$mhs->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Data mahasiswa berhasil dihapus.');

        $this->assertDatabaseMissing('mahasiswa', ['id' => $mhs->id]);
    }

    public function test_halaman_client_bisa_dibuka(): void
    {
        $this->get('/mahasiswa-api')
            ->assertOk()
            ->assertSee('Mahasiswa (REST API)');
    }
}
