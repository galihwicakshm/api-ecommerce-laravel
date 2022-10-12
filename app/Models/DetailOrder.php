<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_detail';
    protected $table = "detailorders";
    protected $fillable = ['no_order', 'id_user', 'id_barang', 'qty'];
}
