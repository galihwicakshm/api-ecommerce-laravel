<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_user = auth()->user()->id_user;
        $cart = Cart::where('id_user', $id_user)->get();
        return response()->json(['status' => 200, 'message' => 'Cart berhasil ditampilkan', 'data' => $cart], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
        $id_user = auth()->user()->id_user;
        $carts = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang)->get();
        $validator = Validator::make($request->all(), [
            'id_barang' => ['required'],
            'qty' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()], 422);
        }
        try {
            $barang = Barang::find($request->id_barang);
            if ($request->qty <= $barang['stok'] && $barang != null) {
                $cart = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
                $carts = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang)->get();
                if ($carts == '[]') {
                    $total = $barang->harga * $request->qty;
                    $cart = Cart::create([
                        'id_barang' => $request->id_barang,
                        'qty' => $request->qty,
                        'id_user' => $id_user,
                        'harga' => $barang->harga,
                        'total' => $total,
                    ]);
                    return response()->json(['status' => 200, 'message' => 'Cart berhasil ditambahkan', 'data' => $cart], 200);
                } else if ($barang['stok'] == $carts[0]->qty || $barang['stok'] < $carts[0]->qty || $request->qty + $carts[0]->qty > $barang['stok']) {
                    return response()->json(['status' => 422, 'message' => 'Melebihi stok'], 422);
                } else if ($carts[0]->id_barang == $request->id_barang) {
                    $cartUpdate = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang);
                    $getCart = $cartUpdate->get();
                    $updateQty = $getCart[0]->qty + $request->qty;
                    $cartUpdate->update(['qty' => $updateQty]);
                    $dataAfter = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang)->get();
                    return response()->json(['status' => 200, 'message' => 'Cart berhasil ditambahkan', 'data' =>  $dataAfter], 200);
                }
            } else if (($request->qty > $barang['stok'] && $barang != null)) {
                return response()->json(['status' => 422, 'message' => 'Melebihi stok'], 422);
            } else {
                return response()->json(['status' => 404, 'errors' => 'Barang tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 404, 'errors' => 'Barang tidak ditemukan'], 404);

            return response()->json(['status' => 500, 'errors' => $th->getMessage()]);
        }
    }


    // $getCart = Cart::where('id_user', $id_user)->where('id_barang', $request->id_barang)->get();

    /**
     * Display the specified resource.
     
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
    public function update(Request $request, $id_barang)
    {
        // $id_user = auth()->user()->id_user;
        // $cart = Cart::where('id_user',   $id_user)->where('id_barang', $id_barang)->get();


        // return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $cart]);
    }

    public function updateCart(Request $request, $id_barang)
    {
        $id_user = auth()->user()->id_user;

        $getcart = Cart::where('id_user', $id_user)->where('id_barang', $id_barang)->get();
        try {
            if ($getcart != '[]') {
                Cart::where('id_barang', $id_barang)->update(['qty' => $request->qty]);
                return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $getcart]);
            } else if ($getcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $th->getMessage()]);
        }

        // $id_user = auth()->user()->id_user;

        // $getcart = Cart::where('id_user', $id_user)->where('id_cart', $request->id_cart);

        // try {
        //     $GG = $getcart->update(['qty' => $request->qty]);
        //     return  response()->json(['status' => 200, 'message' => $getcart->get()]);
        // } catch (\Throwable $th) {
        //     return  response()->json(['status' => 200, 'message' => $th->getMessage()]);
        // }
    }

    public function updateIncrement($id_cart)
    {
        $id_user = auth()->user()->id_user;

        $getcart = Cart::where('id_user', $id_user)->where('id_cart', $id_cart)->get();
        $carz = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
        try {
            if ($getcart[0]->qty  == $carz[0]->stok) {
                return response()->json(['status' => 422, 'errors' => 'Melebihi stok'], 422);
            } else if ($getcart != '[]') {
                $cart = $getcart[0]->qty + 1;
                Cart::where('id_cart', $id_cart)->update(['qty' => $cart]);
                return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $getcart]);
            } else if ($getcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $th->getMessage()]);
        }
    }

    public function updateDecrement($id_cart)
    {
        $id_user = auth()->user()->id_user;
        $carz = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
        $getcart = Cart::where('id_user', $id_user)->where('id_cart', $id_cart)->get();
        try {
            if ($getcart[0]->qty == 1) {
                return response()->json(['status' => 422, 'errors' => 'Minimal pembelian barang harus 1'], 422);
            }
            if ($getcart != '[]') {
                $cart = $getcart[0]->qty - 1;
                Cart::where('id_cart', $id_cart)->update(['qty' => $cart]);
                return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $getcart]);
            } else if ($getcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $th->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_barang)
    {
        $id_user = auth()->user()->id_user;

        $getcart = Cart::where('id_user', $id_user)->where('id_barang', $id_barang)->get();


        try {
            if ($getcart != '[]') {
                Cart::where('id_barang', $id_barang)->delete();
                return response()->json(['status' => 200, 'message' => 'Berhasil dihapus']);
            } else if ($getcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 200, 'message' => 'Berhasil update', 'data' => $th->getMessage()]);
        }
    }

    public function destroyAll($id_user)
    {

        $id_user = auth()->user()->id_user;
        $getcart = Cart::where('id_user', $id_user)->get();
        try {
            if ($getcart != '[]') {
                Cart::where('id_user', $id_user)->delete();
                return response()->json(['status' => 200, 'message' => 'Berhasil dihapus']);
            } else if ($getcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Data tidak ditemukan'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 400, 'message' => 'Berhasil update', 'data' => $th->getMessage()]);
        }
    }
}
