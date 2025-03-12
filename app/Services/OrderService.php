<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private $order;
    public function __construct()
    {
        //
    }

    public function createNewOrder(OrderRequest $request): Order{
        $data = [];
        $user = Auth::user();
        $items = $request->items;

        DB::transaction(function () use ($user, $items) {
            $order = Order::create(['user_id' => $user->id]);
            $discount = $user->status > 2 ? true : false;

            $data = [];
            foreach ($items as $item) {
                $product = Product::where('id', $item['product_id'])->firstOrFail();
                $discount_amount = $discount ? $product->price * 0.25 : 0;

                $data[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'discount' => $discount_amount
                ];
            }

            OrderItem::insert($data);
            $this->order = $order;
        });

        return $this->order;

    }
}
