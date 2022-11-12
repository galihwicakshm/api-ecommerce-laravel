<?php

namespace App\Repositories\Barang;

use LaravelEasyRepository\Repository;

interface BarangRepository extends Repository
{

    public function getAll();

    public function cariBarang($id);

    public function findBarang($id);
}
