<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = DB::select("select * from products");
        return response()->json($products, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product_id = DB::table('products')->insertGetId(
            ['name' => $request->name]
        );
        $product = DB::select('select * from products where id = ?', [$product_id]);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = DB::select('select * from products where id = ?', [$id]);

        if (count($product) == 0) {
            return response()->json(["message" => "Product not found"], 404);
        }

        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $isProductExist = DB::update('update products set name = ? where id = ?', [$request["name"], $id]);

        if (!$isProductExist) {
            return response()->json([
                "message" => "Product not found!"
            ], 404);
        }

        $updatedProduct = DB::select('select * from products where id = ?', [$id]);

        return response()->json($updatedProduct, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
