<?php

use App\Models\Product;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



Artisan::command('test {arg?}', function($arg){
    try{
        $res = Product::where('id', $arg)->firstOrfail()->toArray();
    }catch (\Throwable $e){
        dd($e->getMessage());
    }
    dd($res);
});
