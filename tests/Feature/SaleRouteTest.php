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
    public function test_if_can_store_sale_with_success(): void
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

    public function test_if_return_400_error_when_some_productId_is_missing(): void
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

    public function test_if_return_400_error_when_some_quantity_is_missing(): void
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

    public function test_if_return_404_error_when_some_product_not_exist(): void
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
}
