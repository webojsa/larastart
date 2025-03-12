<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
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

    public function showOrder(int $id):Response{
        try{
            $response = $this->service->getOrder($id);
            return \response($response);
        }catch (\Throwable $e) {
            $code = Str::random(10);
            Log::error('Can not show order',[
                'order_id' => $id,
                'error' => $e,
                'code' => $code
            ]);
            return new Response("Error : $code", ResponseCodes::HTTP_BAD_REQUEST);
        }
    }

    public function updateStatus(Request $request):Response{
        $data = $request->validate([
            'order_id' => 'required | integer | exists:orders,id',
            'status'=>  'required | integer | min:0 | max:5'
        ]);
        try{
            $response = $this->service->updateStatus($data['order_id'], $data['status']);
            return \response($response);
        }catch (\Throwable $e) {
            $code = Str::random(10);
            Log::error('Can not show order',[
                'inputdata' => $data,
                'error' => $e,
                'code' => $code
            ]);
            return new Response("Error : $code", ResponseCodes::HTTP_BAD_REQUEST);
        }
    }



}
