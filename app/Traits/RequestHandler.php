<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait RequestHandler
{
    public function requestToArray(Request $request): array{
        $dataArray = ($request->toArray() !== [])
            ? $request->toArray()
            : $request->json()->all();

        return $dataArray;
    }
}
