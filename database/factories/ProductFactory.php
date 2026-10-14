<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'sku' => fake()->unique()->bothify('VOG-???-####'),
            'name' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 50000, 500000),
            'stock' => fake()->numberBetween(0, 100),
            'minimum_stock' => fake()->numberBetween(3, 10),
            'description' => fake()->paragraph(),
            'image' => 'default_cloth.svg',
        ];
    }
}
