<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $name = fake()->unique()->word() . ' Product';
            Product::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'description' => fake()->text(100),
                'price' => fake()->randomFloat(2, 10, 500),
                'quantity' => fake()->numberBetween(1, 100),
                'image' => null,
                'status' => fake()->boolean(),
            ]);
        }
    }
}
