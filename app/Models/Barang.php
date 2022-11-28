<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_barang';
    protected $fillable = ['id_kategori', 'nama_barang', 'berat', 'stok', 'harga', 'deskripsi', 'gambar_name', 'gambar_url'];
}
