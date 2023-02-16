<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class ValidateExistenceOfProduct
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $sales = $request->all();

        foreach ($sales as $sale) {
            $product = DB::select('select * from products where id = ?', [$sale['productId']]);

            if (count($product) === 0) {
                throw new HttpResponseException(response()->json([
                    "message" => "Product not found!"
                ], 404));
            }
        }

        return $next($request);
    }
}
