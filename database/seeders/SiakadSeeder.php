<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen;
use App\Models\MataKuliah;

class SiakadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            $dataDosen = [
                ['nidn' => '0012345678', 'nama' => 'Dr. Andi Saputra', 'prodi' => 'Informatika'],
                ['nidn' => '0012345679', 'nama' => 'Siti Rahmawati, M.Kom.', 'prodi' => 'Informatika'],
                ['nidn' => '0012345680', 'nama' => 'Budi Santoso, M.T.', 'prodi' => 'Sistem Informasi'],
            ];

            foreach ($dataDosen as $data) {
                $dosen = Dosen::updateOrCreate(
                    ['nidn' => $data['nidn']],
                    ['nama' => $data['nama'], 'prodi' => $data['prodi']],
                );

                $mataKuliah = match ($data['nidn']) {
                    '0012345678' => [
                        ['kode' => 'IF101', 'nama' => 'Algoritma dan Pemrograman', 'sks' => 3],
                        ['kode' => 'IF102', 'nama' => 'Struktur Data', 'sks' => 3],
                    ],
                    '0012345679' => [
                        ['kode' => 'IF201', 'nama' => 'Basis Data', 'sks' => 3],
                        ['kode' => 'IF202', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3],
                    ],
                    default => [
                        ['kode' => 'SI101', 'nama' => 'Pengantar Sistem Informasi', 'sks' => 2],
                        ['kode' => 'SI201', 'nama' => 'Analisis Proses Bisnis', 'sks' => 3],
                    ],
                };

                foreach ($mataKuliah as $dataMataKuliah) {
                    MataKuliah::updateOrCreate(
                        ['kode' => $dataMataKuliah['kode']],
                        [...$dataMataKuliah, 'dosen_id' => $dosen->id],
                    );
                }
            }
        });
    }
}
