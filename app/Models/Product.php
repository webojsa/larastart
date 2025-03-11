<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'stock'
    ];

    public function tags(){
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }
}
