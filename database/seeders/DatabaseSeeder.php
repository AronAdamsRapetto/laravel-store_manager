<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Sale;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Product::factory()->hasAttached(
            Sale::factory()->count(1),
            ["quantity" => 5]
        )->createMany([
            ["name" => "Martelo de Thor"],
            ["name" => "Traje de encolhimento"],
            ["name" => "Escudo do Capitão América"],
        ]);
    }
}
