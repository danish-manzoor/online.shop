<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug,
            'description' => $this->faker->paragraphs(3, true),
            'short_description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'compare_price' => $this->faker->randomFloat(2, 1000, 1500),
            'category_id' => Category::inRandomOrder()->value('id'), // adjust based on real categories
            'brand_id' => Brand::inRandomOrder()->value('id'),  // adjust based on real brands
            'sku' => strtoupper($this->faker->unique()->bothify('SKU###??')),
            'qty' => $this->faker->numberBetween(1, 50),
        ];
    }
}
