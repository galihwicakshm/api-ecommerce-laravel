<?php

namespace App\Repositories\Kategori;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Kategori;

class KategoriRepositoryImplement extends Eloquent implements KategoriRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $kategori;

    public function __construct(Kategori $kategori)
    {
        $this->kategori = $kategori;
    }

    public function getAll()
    {
        $kategori = Kategori::all();
        return $kategori;
    }

    public function findKategori($id)
    {
        $kategori = Kategori::find($id);
        return $kategori;
    }

    // Write something awesome :)
}
