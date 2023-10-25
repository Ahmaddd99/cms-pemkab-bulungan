<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tanggal' => $this->faker->date(),
            'waktu' => $this->faker->time(),
            'lokasi' => $this->faker->city(),
            'judul' => $this->faker->sentence(1),
            'keterangan' => $this->faker->sentence(2)
        ];
    }
}
