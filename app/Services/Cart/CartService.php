<?php

namespace App\Services\Cart;

use Illuminate\Http\Request;
use LaravelEasyRepository\BaseService;

interface CartService extends BaseService
{
    public function store(Request $request, $data);
}
