<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use Database\Factories\ProductTagFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductTag::truncate();
        $productIds = Product::pluck('id');
        $tagIds = Tag::pluck('id');
       // dd($tagIds);
        foreach($productIds as $productId){
            $randomTags = $tagIds->random(rand(1,3));
            foreach($randomTags as $tagId){
                ProductTag::factory()->create([
                    'product_id' => $productId,
                    'tag_id' => $tagId
                ]);
            }
        }
    }
}
