<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sales = $request->all();
        $sale_id = DB::table('sales')->insertGetId(["created_at" => now(), "updated_at" => now()]);
        foreach ($sales as $sale) {
            DB::insert(
                'insert into sales_products (sale_id, product_id, quantity) values (?, ?, ?)',
                [$sale_id, $sale["productId"], $sale["quantity"]]
            );
        }
        return response()->json(["id" => $sale_id, "itemsSold" => $sales], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
