<?php

namespace App\Services;

use App\Interfaces\Interfaces\IsProductAvailable;
use App\Models\Product;
use App\Traits\RequestHandler;
use Illuminate\Http\Request;

class ProductService implements IsProductAvailable
{

    use RequestHandler;

    private $stock_minimum = 2;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createNewProduct(Request $request):array{
        $fields = $this->maper($request);
        $product = Product::create($fields);
        return $product->toArray();
    }

    public function deleteProduct(int $productId):bool{
        $product = Product::where('id', $productId)->firstOrFail();
        return $product->delete();
    }

    public function maper(Request $request):array{
        $data = $this->requestToArray($request);
        $map = [
          'title' => $data['title'],
          'price' => (float)number_format($data['price'],2),
        ];
        $stock = $data['stock'] ?? 0;
        $map['stock'] = $stock;
        return $map;
    }

    public function onStock(Product $product): bool
    {
        return $product->stock >= $this->stock_minimum;
    }

    public function isExpired(Product $product): bool
    {
        return false;
    }
}
