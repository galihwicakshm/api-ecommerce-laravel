<?php

namespace App\Repositories\Cart;

use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface CartRepository extends Repository
{

    public function getAll();

    public function store($data);

    public function joinBarangAuthID();

    public function joinBarangWhere(Request $request);
}
