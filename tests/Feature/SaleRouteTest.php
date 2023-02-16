<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;


class SaleRouteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_if_route_post_sale_can_store_sale_with_success(): void
    {
        $requestContent = [
            [
                "productId" => 1,
                "quantity" => 1
            ],
            [
                "productId" => 2,
                "quantity" => 5
            ]
        ];

        $responseContent = [
            "id" => 4,
            "itemsSold" => $requestContent
        ];

        DB::shouldReceive('select')->times(2)->andReturn(["some product"]);
        DB::shouldReceive('table->insertGetId')->once()->andReturn($responseContent["id"]);
        DB::shouldReceive('insert')->once();

        $response = $this->postJson('api/sale', $requestContent);

        $response->assertStatus(201);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_post_sale_return_400_error_when_some_productId_is_missing(): void
    {
        $requestContent = [
            [
                "productId" => 1,
                "quantity" => 1
            ],
            [
                "quantity" => 5
            ],
            [
                "productId" => 2,
                "quantity" => 5
            ]
        ];

        $responseContent = [
            "message" => "productId is required"
        ];

        $response = $this->postJson('api/sale', $requestContent);

        $response->assertStatus(400);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_post_sale_return_400_error_when_some_quantity_is_missing(): void
    {
        $requestContent = [
            [
                "productId" => 1,
                "quantity" => 1
            ],
            [
                "productId" => 2,
                "quantity" => 5
            ],
            [
                "productId" => 2,
            ]
        ];

        $responseContent = [
            "message" => "quantity is required"
        ];

        $response = $this->postJson('api/sale', $requestContent);

        $response->assertStatus(400);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_post_sale_return_404_error_when_some_product_not_exist(): void
    {
        $requestContent = [
            [
                "productId" => 1,
                "quantity" => 1
            ],
            [
                "productId" => 99,
                "quantity" => 5
            ],
            [
                "productId" => 3,
                "quantity" => 4
            ]
        ];

        $responseContent = [
            "message" => "Product not found!"
        ];
        DB::shouldReceive('select')->once()->andReturn(["some product"]);
        DB::shouldReceive('select')->once()->andReturn([]);

        $response = $this->postJson('api/sale', $requestContent);

        $response->assertStatus(404);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_get_sale_return_all_sales()
    {
        $responseContent = [
            [
                "sale_id" => 1,
                "date" => "2023-02-16 17:02:29",
                "product_id" => 2,
                "quantity" => 4
            ],
            [
                "sale_id" => 2,
                "date" => "2023-02-16 17:02:28",
                "product_id" => 1,
                "quantity" => 8
            ],
        ];

        DB::shouldReceive('table->join->select->get')->once()->andReturn($responseContent);

        $response = $this->getJson('api/sale');

        $response->assertStatus(200);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_get_sale_by_id_return_one_sale()
    {
        $responseContent = [
            [
                "date" => "2023-02-16 17:02:29",
                "product_id" => 2,
                "quantity" => 4
            ],
            [
                "date" => "2023-02-16 17:02:28",
                "product_id" => 1,
                "quantity" => 8
            ],
        ];

        DB::shouldReceive('table->join->select->where->get')->once()->andReturn($responseContent);

        $response = $this->getJson('api/sale/1');

        $response->assertStatus(200);
        $response->assertExactJson($responseContent);
    }

    public function test_if_route_get_sale_by_id_return_404_error_when_sale_not_exist()
    {
        $responseContent = [
            "message" => 'Sale not found!'
        ];

        DB::shouldReceive('table->join->select->where->get')->once()->andReturn([]);

        $response = $this->getJson('api/sale/99');

        $response->assertStatus(404);
        $response->assertExactJson($responseContent);
    }
}
