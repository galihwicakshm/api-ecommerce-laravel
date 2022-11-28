<?php

namespace App\Services\Barang;

use LaravelEasyRepository\BaseService;

interface BarangService extends BaseService
{

    public function store($data);
}
