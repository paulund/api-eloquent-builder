<?php

namespace Paulund\ApiQueryBuilder\Tests\Mocks\Http\Controllers;

use Illuminate\Routing\Controller;
use Paulund\ApiQueryBuilder\Tests\Mocks\Models\Product;

class ProductController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'data' => Product::get()
        ]);
    }
}