<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'subcategory_id' => Subcategory::factory(),
            'image' => $this->faker->imageUrl(300, 300, 'animals', true),
            'title' => $this->faker->sentence(2),
            'body' => $this->faker->sentence(50),
            'meta' => $this->faker->country
        ];
    }
}
