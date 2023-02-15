<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductRouteTest extends TestCase
{
    public function test_if_return_a_list_of_products_with_status_200(): void
    {

        $responseContent = [
            [
                "id" => 1,
                "name" => "Xablau",
            ],
            [
                "id" => 2,
                "name" => "Alguma coisa",
            ]
        ];

        DB::shouldReceive("select")->once()->andReturn($responseContent);

        $response = $this->getJson('/api/product');

        $response->assertExactJson($responseContent);
        $response->assertStatus(200);
    }

    public function test_if_return_one_product_with_status_200(): void
    {

        $responseContent = [
            [
                "id" => 1,
                "name" => "Martelo de Thor"
            ]
        ];

        DB::shouldReceive("select")->once()->andReturn($responseContent);

        $response = $this->getJson('/api/product');

        $response->assertExactJson($responseContent);
        $response->assertStatus(200);
    }
}
