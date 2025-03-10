<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:run {arg?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Only for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $arg = $this->argument('arg');

        try{
            $result = $this->getProduct($arg);
        }catch (\Throwable $e){
            dd($e->getMessage());
        }
        dd($result);

    }

    private function getProduct($productId)
    {
        $product = Product::where('id',$productId)->firstOrFail()->toArray();
        return $product;
    }
}
