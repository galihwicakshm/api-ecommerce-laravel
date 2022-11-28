<?php

namespace App\Services\Barang;

use LaravelEasyRepository\Service;
use App\Repositories\Barang\BarangRepository;

class BarangServiceImplement extends Service implements BarangService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(BarangRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }


  public function store($data)
  {
    $cart = $this->mainRepository->store($data);
    return $cart;
  }

  // Define your custom methods :)
}
