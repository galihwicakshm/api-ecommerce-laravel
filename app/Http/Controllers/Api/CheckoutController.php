<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $barang = DB::table('barangs')->join('carts', 'barangs.id_barang', '=', 'carts.id_barang')->where('carts.id_user', $id_user, 'AND', 'carts.id_carts')->get();
        // $cart = DB::table('carts')->where('id_user', $id_user);
        $id_user = auth()->user()->id_user;

        $cart = Cart::where('id_user', $id_user)->get();
        // $checkout = $barang[0]->stok - $barang[0]->qty;

        try {
            $id_cart = $cart[0]->id_cart;
            // $id_barang = $cart[0]->id_barang;

            $barang = DB::table('barangs')->join('carts', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_cart', $id_cart)->get();
            // $updatebarang = $barang->where('id_barang', $id_barang)->where('carts', $id_cart);
            $updatebarang = DB::table('barangs')->join('carts', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_cart', $id_cart);

            $stok = $barang[0]->stok - $cart[0]->qty;

            $updatebarang->update(['stok' =>  $stok]);
            $getcart = Cart::where('id_cart', $id_cart);
            $getcart->delete();

            // $getbarang = DB::table('barangs')->join('carts', 'barangs.id_barang', '=', 'carts.id_barang', 'AND', 'barangs.id_barang', '=', 'carts.id_barang')->where('barangs.id_barang', $id_user);

            // $getbarang->update(['stok' => $checkout]);
            return response()->json(['status' => 200, 'message' => $barang]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 400, 'errors' => 'Gagal dihapus'], 400);
        }



        // $cart->delete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
