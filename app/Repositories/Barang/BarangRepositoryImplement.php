<?php

namespace App\Repositories\Barang;

use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Implementations\Eloquent;

class BarangRepositoryImplement extends Eloquent implements BarangRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $barang;

    public function __construct(Barang $barang)
    {
        $this->barang = $barang;
    }

    public function getAll()
    {
        $barang = Barang::join('kategoris', 'barangs.id_kategori', '=', 'kategoris.id_kategori')->select('id_barang', 'nama_barang', 'nama_kategori', 'berat', 'harga', 'stok', 'barangs.created_at', 'barangs.updated_at')->paginate(10);
        return response()->json(['status' => 200, 'message' => 'Barang berhasil ditampilkan', 'data' => $barang], 200);
    }

    public function cariBarang($id)
    {
        $barang = DB::table('barangs')->select('barangs.nama_barang', 'kategoris.nama_kategori', 'barangs.berat', 'barangs.harga', 'barangs.stok', 'barangs.deskripsi')->join('kategoris', 'barangs.id_kategori', '=', 'kategoris.id_kategori')->where('barangs.id_barang', $id)->get();
        return $barang;
    }

    public function findBarang($id)
    {
        $barang = Barang::find($id);
        return $barang;
    }
}
