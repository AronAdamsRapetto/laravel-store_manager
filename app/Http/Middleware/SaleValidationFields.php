<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class SaleValidationFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rules = [
            'row.*.productId' => 'required',
            'row.*.quantity' => ['required', 'integer', 'min:1'],
        ];

        $messages = [
            'required' => ':attribute is required',
            'min' => ':attribute must be greater than or equal to 1',
        ];

        $attributes = [
            'row.*.productId' => 'productId',
            'row.*.quantity' => 'quantity',
        ];

        $data = ['row' => $request->all()];

        $validator = Validator::make($data, $rules, $messages, $attributes);
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'message' => implode(collect($validator->errors())->first()),
            ], 400));
        }

        return $next($request);
    }
}
