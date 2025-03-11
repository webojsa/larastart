<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ProductController extends Controller
{
    protected $service;

    public function __construct(ProductService $productService){
        $this->service = $productService;
    }

    public function newProduct(ProductRequest $request):Response{
        try{
            $response = $this->service->createNewProduct($request);
            return \response($response);
        }catch (\Throwable $e) {
            Log::error([
                'request' => $request->all(),
                'error' => $e->getMessage()
            ]);
            return new Response('eror', ResponseCodes::HTTP_BAD_REQUEST);
        }
    }
}
