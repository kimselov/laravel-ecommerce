<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
             [
                "name" => "Nike",
                "slug" => "Nike",
                "description" => "This is the best brand",
             ],
             [
                "name" => "Addidas",
                "slug" => "Addidas",
                "description" => "This is one of the best brand",
             ],
             [
                "name" => "Nike",
                "slug" => "Nike",
                "description" => "This is the one of the best brand",
             ],
            ];
        foreach($brands as $brand){
              Brand::create($brand);
        }
    }
}
