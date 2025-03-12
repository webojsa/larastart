<?php

namespace App\Services;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class OrderService
{
    private $order;
    public function __construct()
    {
        //
    }

    public function createNewOrder(OrderRequest $request): Order{
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

    public function getOrder(int $order_id):array{
        $order = Order::where('id', $order_id)->firstOrFail();
        Gate::authorize('view', $order);
        $q = DB::table('orders as o')
            ->select("o.id", "o.status as order_status", "oi.quantity", "oi.price", "oi.discount"
                            , "p.title as product_title"
                            , "o.created_at","o.updated_at")
            ->selectRaw("(oi.price - oi.discount) * oi.quantity as item_total")
            ->join('order_items as oi','o.id','=', 'oi.order_id')
            ->join('products as p', 'oi.product_id', '=', 'p.id')
            ->where('o.id', '=', "$order_id");

       $rawData = $q->get();
       $data = [];
        $total = 0;
       foreach($rawData as $index => $r){
           $data['order_id'] = $r->id;
           $data['order_status'] = $r->order_status;
           $data['order_created_at'] = $r->created_at;
           $data['order_updated_at'] = $r->updated_at;
           $data['order_items'][$index]['product'] = $r->product_title;
           $data['order_items'][$index]['quantity'] = $r->quantity;
           $data['order_items'][$index]['price'] = $r->price;
           $data['order_items'][$index]['discount'] = $r->discount;
           $data['order_items'][$index]['item_total'] = $r->item_total;
           $total += $r->item_total;
       }
       $data['order_total'] = number_format($total,2);

       return $data;
    }

    public function updateStatus(int $order_id, $status){
        $order = Order::where('id', $order_id)->firstOrfail();
        Gate::authorize('modify',$order);
        $order->status = $status;
        return  $order->save();
    }
}
