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

        $response = $this->getJson('/api/product/1');

        $response->assertExactJson($responseContent);
        $response->assertStatus(200);
    }


    public function test_if_return_an_error_message_with_status_404(): void
    {

        $responseContent = ["message" => "Product not found"];

        DB::shouldReceive("select")->once()->andReturn([]);

        $response = $this->getJson('/api/product/1');

        $response->assertExactJson($responseContent);
        $response->assertStatus(404);
    }

    public function test_if_return_a_new_product_when_create_with_status_201(): void
    {
        $insertResponse = "4";
        $responseContent = [
            [
                "id" => 4,
                "name" => "ProductX"
            ]
        ];

        DB::shouldReceive("table->insertGetId")->once()->andReturn($insertResponse);
        DB::shouldReceive("select")->once()->andReturn($responseContent);

        $response = $this->postJson('/api/product', ['name' => 'ProductX']);

        $response->assertExactJson($responseContent);
        $response->assertStatus(201);
    }

    public function test_if_return_an_error_status_400_when_missing_name_field(): void
    {
        $responseContent = [
            "message" => "name is required"
        ];


        $response = $this->postJson('/api/product', ['na' => 'ProductX']);

        $response->assertExactJson($responseContent);
        $response->assertStatus(400);
    }

    public function test_if_return_an_error_status_400_when_name_field_is_too_short(): void
    {
        $responseContent = [
            "message" => "name must be at least 5 characters long"
        ];

        $response = $this->postJson('/api/product', ['name' => 'P']);

        $response->assertExactJson($responseContent);
        $response->assertStatus(400);
    }
}
