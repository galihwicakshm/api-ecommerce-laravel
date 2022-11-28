<?php

namespace App\Repositories\Cart;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartRepositoryImplement extends Eloquent implements CartRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $cart;

    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }


    public function getAll()
    {
        $id_user = auth()->user()->id_user;
        $cart = Cart::where('id_user', $id_user)->get();
        return response()->json(['status' => 200, 'message' => 'Cart berhasil ditampilkan', 'data' => $cart], 200);
    }

    public function store($data)
    {
        $cart = Cart::create($data);
        return $cart;
    }

    public function joinBarangAuthID()
    {
        $id_user = auth()->user()->id_user;
        $cart = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user);
        return $cart;
    }

    public function joinBarangWhere(Request $request)
    {
        $id_user = auth()->user()->id_user;
        $cart = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang);
        return $cart;
    }

    // Write something awesome :)
}
