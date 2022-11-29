<?php

namespace App\Repositories\Barang;

use Illuminate\Http\Request;
use LaravelEasyRepository\Repository;

interface BarangRepository extends Repository
{

    public function getAll();

    public function findBarangKategori($id);

    public function findBarang($id);

    public function store(Request $request);

    public function updates(Request $request, $id);
}
