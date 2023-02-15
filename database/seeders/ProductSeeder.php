<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::factory()->createMany([
            ["name" => "Martelo de Thor"],
            ["name" => "Traje de encolhimento"],
            ["name" => "Escudo do Capitão América"],
        ]);
    }
}
