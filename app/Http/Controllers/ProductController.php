<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
            Log::error('Product not created',[
                'request' => $request->all(),
                'error' => $e->getMessage()
            ]);
            return new Response('eror', ResponseCodes::HTTP_BAD_REQUEST);
        }
    }

    public function deleteProduct($productId):Response{
        try{
           $this->service->deleteProduct($productId);
           return response('Product deleted');
        }catch (\Throwable $e){
            $code = Str::random(10);
            Log::error('Product not deleted',[
                'productId' => $productId,
                'error' => $e->getMessage(),
                'code' => $code
            ]);
            return new Response("eror:$code", ResponseCodes::HTTP_BAD_REQUEST);
        }
    }
}
