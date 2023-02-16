<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Sale;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SaleProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sale_id' => Sale::pluck('id')->random(), // forma de extrair um id aleatório
            'product_id' => Product::inRandomOrder()->first(), // outra forma de extrair
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
