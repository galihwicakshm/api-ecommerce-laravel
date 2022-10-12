<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->integer('id_user');
            $table->string('no_order');
            $table->date('tanggal_order');
            $table->string('nama_penerima');
            $table->string('alamat');
            $table->string('telp_penerima');
            $table->integer('total_berat');
            $table->integer('ongkir');
            $table->integer('total_bayar');
            $table->integer('status_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
}
