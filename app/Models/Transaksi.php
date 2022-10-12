<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_transaksi';
    protected $fillable = ['id_user', 'no_order', 'tanggal_order', 'nama_penerima', 'alamat', 'telp_penerima', 'total_berat', 'ongkir', 'total_bayar', 'status_bayar'];
}
