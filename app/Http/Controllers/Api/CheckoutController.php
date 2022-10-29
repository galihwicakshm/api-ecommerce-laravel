<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        // $id_user = auth()->user()->id_user;
        // $cart = Cart::where('id_user', $id_user)->get();
        // // $test = $cart[0]->id_barang;
        // // $last = $cart->where('id_barang', $test);
        // return response()->json(['status' => 200, 'message' => $cart]);
    }


    // public function checkout()
    // {
    //     $id_user = auth()->user()->id_user;
    //     $cart = DB::table('carts')->join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
    //     try {
    //         if ($cart) {
    //             foreach ($cart as $cart) {
    //                 $id_barang = $cart->id_barang;
    //                 // $carts = DB::table('carts')->join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->where('barangs.id_barang', $id_barang);
    //                 $carts = Barang::where('id_barang', $id_barang);
    //                 $kurang = $cart->stok - $cart->qty;
    //                 $carts->update(['stok' => $kurang]);
    //                 $check =  DB::table('carts')->where('id_user', $id_user)->where('id_barang', $id_barang);
    //                 $check->delete();
    //             }
    //             return response()->json(['status' => 200, 'message' =>  true]);
    //         } else {
    //             return response()->json(['status' => 200, 'message' =>  'error']);
    //         }
    //     } catch (\Throwable $th) {
    //         return response()->json(['status' => 200, 'message' =>  $th->getMessage()]);
    //     }


    //     // $id_user = auth()->user()->id_user;

    //     // $cart =  Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();


    //     // try {
    //     //     foreach ($cart as $cart) {
    //     //         $kurang = $cart->stok - $cart->qty;
    //     //         $id_barang = $cart->id_barang;
    //     //         $carts = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->where('barangs.id_barang', $id_barang);
    //     //         $carts->update(['stok' => $kurang]);
    //     //         $check =  Cart::where('id_user', $id_user)->where('id_barang', $id_barang);
    //     //         $check->delete();
    //     //         return response()->json(['status' => 200, 'message' =>  $cart]);
    //     //     }
    //     // } catch (\Throwable $th) {
    //     //     return response()->json(['status' => 200, 'message' =>  $th->getMessage()]);
    //     // }
    // }


    public function checkout(Request $request)
    {
        $id_user = auth()->user()->id_user;
        $cart = DB::table('carts')->join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
        $validatorTransaksi = Validator::make($transaksi = $request->all(), [
            // 'id_user' => ['required'],
            'no_order' => ['required'],
            'tanggal_order' => ['required'],
            'nama_penerima' => ['required'],
            'alamat' => ['required'],
            'telp_penerima' => ['required'],
            'total_berat' => ['required'],
            'ongkir' => ['required'],
            'total_bayar' => ['required'],
            'status_bayar' => ['required'],
        ]);

        $validatorDetail = Validator::make($detail = $request->all(), [
            // 'id_user' => ['required'],
            'no_order' => ['required'],
        ]);

        if ($validatorDetail->fails() && $validatorTransaksi->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors(), 'detail_order' => $validatorDetail->errors()], 400);
        } else if ($validatorDetail->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorDetail->errors()], 400);
        } else if ($validatorTransaksi->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors()], 400);
        }



        try {

            if ($cart) {
                foreach ($cart as $cart) {
                    $id_barang = $cart->id_barang;
                    $detail = DetailOrder::create([
                        'no_order' => $transaksi['no_order'],
                        'id_user' =>  $id_user,
                        'id_barang' => $id_barang,
                        'qty' => $cart->qty

                    ]);
                    $carts = Barang::where('id_barang', $id_barang);
                    $kurang = $cart->stok - $cart->qty;
                    $carts->update(['stok' => $kurang]);
                    $check =  DB::table('carts')->where('id_user', $id_user)->where('id_barang', $id_barang);
                    $check->delete();
                }
                $barang = Transaksi::create([
                    'id_user' => $id_user,
                    'no_order' => $request->no_order,
                    'tanggal_order' => $request->tanggal_order,
                    'nama_penerima' => $request->nama_penerima,
                    'alamat' => $request->alamat,
                    'ongkir' => $request->ongkir,
                    'telp_penerima' => $request->telp_penerima,
                    'total_berat' => $request->total_berat,
                    'total_bayar' => $request->total_bayar,
                    'status_bayar' => $request->status_bayar,
                ]);




                return response()->json(['status' => 200, 'meesage' => 'Transaksi berhasil ditambahkan', 'data'  =>  $barang, 'detail_order' => $detail], 200);
            } else {
                return response()->json(['status' => 400, 'message' =>  'error']);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' =>  $th->getMessage()]);
        }
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
