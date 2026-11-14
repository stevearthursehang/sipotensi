<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kelurahan>
 */
class KelurahanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->city,
            'kecamatan' => $this->faker->city,
            'alamat_kantor' => $this->faker->address,
            'jumlah_penduduk' => $this->faker->numberBetween(1000, 10000),
            'tenaga_kerja_tersedia' => $this->faker->numberBetween(500, 5000),
            'akses_jalan' => $this->faker->boolean,
            'listrik' => $this->faker->boolean,
            'air_bersih' => $this->faker->boolean,
            'internet' => $this->faker->boolean,
        ];
    }
}
