<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductRouteTest extends TestCase
{
    public function test_if_get_product_return_a_list_of_products_with_status_200(): void
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

    public function test_if_get_product_by_id_return_one_product_with_status_200(): void
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


    public function test_if_get_product_by_id_return_a_error_message_with_status_404_when_product_not_exist(): void
    {

        $responseContent = ["message" => "Product not found!"];

        DB::shouldReceive("select")->once()->andReturn([]);

        $response = $this->getJson('/api/product/1');

        $response->assertExactJson($responseContent);
        $response->assertStatus(404);
    }

    public function test_if_store_product_route_return_a_new_product_with_status_201(): void
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

    public function test_if_store_product_route_return_a_error_status_400_when_missing_name_field(): void
    {
        $responseContent = [
            "message" => "name is required"
        ];


        $response = $this->postJson('/api/product', ['na' => 'ProductX']);

        $response->assertExactJson($responseContent);
        $response->assertStatus(400);
    }

    public function test_if_store_product_route_return_a_error_status_400_when_name_field_is_too_short(): void
    {
        $responseContent = [
            "message" => "name must be at least 5 characters long"
        ];

        $response = $this->postJson('/api/product', ['name' => 'P']);

        $response->assertExactJson($responseContent);
        $response->assertStatus(400);
    }

    public function test_if_update_product_by_id_route_return_a_updated_product_with_status_200()
    {
        $responseContent = [
            [
                "id" => 1,
                "name" => "ProdutoY"
            ]
        ];

        $requestContent = [
            "name" => "ProdutoY"
        ];

        DB::shouldReceive('select')->once()->andReturn(["some product"]);
        DB::shouldReceive('update')->once();
        DB::shouldReceive('select')->once()->andReturn($responseContent);

        $response = $this->putJson('/api/product/1', $requestContent);

        $response->assertStatus(200);
        $response->assertExactJson($responseContent);
    }

    public function test_if_update_product_by_id_route_return_a_error_status_400_when_missing_field_name()
    {
        $responseContent = [
            "message" => "name is required"
        ];

        $response = $this->putJson('/api/product/1');

        $response->assertStatus(400);
        $response->assertExactJson($responseContent);
    }

    public function test_if_update_product_by_id_route_return_a_error_status_400_when_field_name_is_too_short()
    {
        $responseContent = [
            "message" => "name must be at least 5 characters long"
        ];

        $requestContent = [
            "name" => "P"
        ];

        $response = $this->putJson('/api/product/1', $requestContent);

        $response->assertStatus(400);
        $response->assertExactJson($responseContent);
    }

    public function test_if_update_product_by_id_route_return_a_error_status_400_when_product_not_exist()
    {
        $responseContent = [
            "message" => "Product not found!"
        ];

        $requestContent = [
            "name" => "ProdutoY"
        ];

        DB::shouldReceive('select')->once()->andReturn([]);

        $response = $this->putJson('/api/product/99', $requestContent);

        $response->assertStatus(404);
        $response->assertExactJson($responseContent);
    }

    public function test_if_delete_product_return_nothing_with_status_204()
    {
        DB::shouldReceive('select')->once()->andReturn(["some product"]);
        DB::shouldReceive('delete')->once();

        $response = $this->deleteJson('api/product/1');

        $response->assertStatus(204);
        $response->assertNoContent();
    }

    public function test_if_delete_product_return_a_error_404_when_product_not_exist()
    {
        $responseContent = [
            "message" => "Product not found!"
        ];

        DB::shouldReceive('select')->once()->andReturn([]);

        $response = $this->deleteJson('api/product/1');

        $response->assertStatus(404);
        $response->assertExactJson($responseContent);
    }

    public function test_if_get_product_by_query_param_return_the_products_with_status_200()
    {
        $responseContent = [
            [
                "id" => 2,
                "name" => "Traje de encolhimento"
            ]
        ];

        DB::shouldReceive('select')->once()->andReturn($responseContent);

        $response = $this->getJson('api/product/search?q=traje');

        $response->assertStatus(200);
        $response->assertExactJson($responseContent);
    }

    public function test_if_get_product_by_query_param_with_empty_query_return_all_the_products_with_status_200()
    {
        $responseContent = [
            [
                "id" => 1,
                "name" => "Martelo de Thor"
            ],
            [
                "id" => 2,
                "name" => "Traje de encolhimento"
            ],
            [
                "id" => 3,
                "name" => "Escudo do Capitão América"
            ]
        ];

        DB::shouldReceive('select')->once()->andReturn($responseContent);

        $response = $this->getJson('api/product/search?q=');

        $response->assertStatus(200);
        $response->assertExactJson($responseContent);
    }
}
