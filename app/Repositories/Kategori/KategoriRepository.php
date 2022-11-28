<?php

namespace App\Repositories\Kategori;

use LaravelEasyRepository\Repository;

interface KategoriRepository extends Repository
{

    public function getAll();

    public function findKategori($id);
}
