<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subcategory>
 */
class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::all();
        return [
            'category_id' => $this->faker->randomElement($category)->id,
            'name' => $this->faker->sentence(2),
            'image' => $this->faker->imageUrl(300, 300, 'animals', true),
            'published' => $this->faker->boolean
        ];
    }
}
