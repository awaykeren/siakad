<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim' => fake()->unique()->numerify('24####'),
            'nama' => fake()->name(),
            'prodi' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
            ]),
            'nohp' => fake()->numerify('08##########'),
            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
