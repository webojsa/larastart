<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class OrderController extends Controller
{
    protected $service;

    public function __construct(OrderService $orderService){
        $this->service = $orderService;
    }

    public function createOrder(OrderRequest $request):Response{
        try{
            $response = $this->service->createNewOrder($request);
            return \response($response);
        }catch (\Throwable $e) {
            $code = Str::random(10);
            Log::error('Order not created',[
                'request' => $request->all(),
                'error' => $e,
                'code' => $code
            ]);
            return new Response("Error : $code", ResponseCodes::HTTP_BAD_REQUEST);
        }
    }


}
