<?php

namespace App\Interfaces\Interfaces;

use App\Models\Product;

interface IsProductAvailable
{
    public function onStock(Product $product) :bool;
    public function isExpired(Product $product):bool;
}
