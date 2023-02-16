<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ValidateExistenceOfProduct
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        if (Str::contains($path, 'product')) {
            $product_id = $request->route('id');

            $product = DB::select('select * from products where id = ?', [$product_id]);

            if (count($product) === 0) {
                throw new HttpResponseException(response()->json([
                    "message" => "Product not found!"
                ], 404));
            }
        } else {
            $sales = $request->all();

            foreach ($sales as $sale) {
                $product = DB::select('select * from products where id = ?', [$sale['productId']]);

                if (count($product) === 0) {
                    throw new HttpResponseException(response()->json([
                        "message" => "Product not found!"
                    ], 404));
                }
            }
        }


        return $next($request);
    }
}
