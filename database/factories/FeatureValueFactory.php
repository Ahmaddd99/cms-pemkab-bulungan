<?php

namespace Database\Factories;

use App\Models\Content;
use App\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeatureValue>
 */
class FeatureValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $feature = Feature::all();
        $content = Content::all();
        return [
            'feature_id' => $this->faker->randomElement($feature)->id,
            'content_id' => $this->faker->randomElement($content)->id,
        ];
    }
}
