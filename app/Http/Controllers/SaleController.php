<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // QUERY BRUTA
        // $sales = DB::select(
        //     'select sa.id as saleId, sa.created_at as date, sa_pr.product_id as productId, sa_pr.quantity as quantity from sales as sa join sales_products as sa_pr on sa.id = sa_pr.sale_id'
        // );
        // QUERY BUILDER
        $sales = DB::table('sales as sa')
            ->join('sales_products as sa_pr', 'sa.id', '=', 'sa_pr.sale_id')
            ->select('sa.id as sale_id', 'sa.created_at as date', 'sa_pr.product_id', 'sa_pr.quantity')
            ->get();

        return response()->json($sales, 200);
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
        $sales = DB::table('sales as sa')
            ->join('sales_products as sa_pr', 'sa.id', '=', 'sa_pr.sale_id')
            ->select('sa.created_at as date', 'sa_pr.product_id', 'sa_pr.quantity')
            ->where('sa.id', '=', $id)
            ->get();

        if (count($sales) === 0) {
            return response()->json([
                "message" => 'Sale not found!'
            ], 404);
        }

        return response()->json($sales, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sale = DB::select('select * from sales where id = ?', [$id]);

        if (!count($sale)) {
            return response()->json([
                'message' => 'Sale not found!'
            ], 404);
        }

        $salesToUpdate = $request->all();

        foreach ($salesToUpdate as $sale) {
            DB::update(
                'update sales_products set quantity = ? where sale_id = ? and product_id = ?',
                [$sale['quantity'], $id, $sale["productId"]]
            );
        }

        return response()->json([
            "saleId" => intval($id),
            "itemsSold" => $salesToUpdate
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = DB::select('select * from sales where id = ?', [$id]);

        if (!count($sale)) {
            return response()->json([
                "message" => "Sale not found!"
            ], 404);
        }

        DB::delete('delete from sales where id = ?', [$id]);

        return response('No-content', 204);
    }
}
