<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Agenda;
use App\Models\Attribut;
use App\Models\AttributesValue;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Content;
use App\Models\ContentGallery;
use App\Models\Feature;
use App\Models\FeatureValue;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(10)->create();
        Subcategory::factory(10)->create();
        Content::factory(10)->create();
        Banner::factory(10)->create();
        Agenda::factory(10)->create();
        Attribut::factory(10)->create();
        AttributesValue::factory(10)->create();
        ContentGallery::factory(10)->create();
        Feature::factory(10)->create();
        FeatureValue::factory(10)->create();

        $this->call(UserSeeder::class);
    }
}
