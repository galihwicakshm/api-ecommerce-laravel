<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_cart';
    protected $table = 'carts';
    protected $fillable = ['id_user', 'id_barang', 'harga', 'total', 'qty'];
}
