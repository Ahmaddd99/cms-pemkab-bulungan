<?php

namespace Database\Factories;

use App\Models\Attribut;
use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AttributesValue>
 */
class AttributesValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content_id' => Content::factory(),
            'attribut_id' => Attribut::factory(),
            'description' => $this->faker->sentence(2),
            'order' => $this->faker->randomDigit()
        ];
    }
}
