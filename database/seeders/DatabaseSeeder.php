<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Product::factory()->createMany([
            ["name" => "Martelo de Thor"],
            ["name" => "Traje de encolhimento"],
            ["name" => "Escudo do Capitão América"],
        ]);

        Sale::factory(3)->create();

        SaleProduct::factory(3)->create();
    }
}
