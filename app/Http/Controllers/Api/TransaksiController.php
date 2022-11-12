<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Barang;
use App\Models\Transaksi;
use App\Models\DetailOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $transaksi = DB::table('transaksis')->paginate(10);

        $transaksi = Transaksi::join('detailorders', 'transaksis.no_order', '=', 'detailorders.no_order')->distinct('detailorders.no_order')->paginate(10);
        // // $transaksi = DB::table('transaksis');

        // // $data = array(
        // //     'transaksi' => Transaksi::where('no_order', $no_order),
        // // );



        return response()->json(['status' => 200, "message" => "Transaksi berhasil ditampilkan", "data" => $transaksi], 200);
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {

    //     $id_user = auth()->user()->id_user;

    //     $validatorTransaksi = Validator::make($transaksi = $request->all(), [
    //         'no_order' => ['required'],
    //         'tanggal_order' => ['required'],
    //         'nama_penerima' => ['required'],
    //         'alamat' => ['required'],
    //         'telp_penerima' => ['required'],
    //         'total_berat' => ['required'],
    //         'ongkir' => ['required'],
    //         'total_bayar' => ['required'],
    //         'status_bayar' => ['required'],
    //     ]);

    //     $validatorDetail = Validator::make($detail = $request->all(), [
    //         'no_order' => ['required'],


    //     ]);

    //     if ($validatorDetail->fails() && $validatorTransaksi->fails()) {
    //         return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors(), 'detail_order' => $validatorDetail->errors()], 400);
    //     } else if ($validatorDetail->fails()) {
    //         return response()->json(['status' => 400, 'errors' => $validatorDetail->errors()], 400);
    //     } else if ($validatorTransaksi->fails()) {
    //         return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors()], 400);
    //     }

    //     try {

    //         $barang = Transaksi::create([
    //             'id_user' => $id_user,
    //             'no_order' => $request->no_order,
    //             'tanggal_order' => $request->tanggal_order,
    //             'nama_penerima' => $request->nama_penerima,
    //             'alamat' => $request->alamat,
    //             'ongkir' => $request->ongkir,
    //             'telp_penerima' => $request->telp_penerima,
    //             'total_berat' => $request->total_berat,
    //             'total_bayar' => $request->total_bayar,
    //             'status_bayar' => $request->status_bayar,
    //         ]);

    //         $detail = DetailOrder::create([
    //             'no_order' => $request->no_order,
    //             'id_user' =>  $id_user,
    //         ]);
    //         $qtybarang = Barang::find($request->id_barang);
    //         $qtybarangs = DB::table('barangs')->where('id_barang', $request->id_barang);
    //         $qtyminus = ($qtybarang['stok'] - $request->qty);
    //         $qtybarangs->update([
    //             'stok' => $qtyminus
    //         ]);
    //         return response()->json(['status' => 200, 'meesage' => 'Transaksi berhasil ditambahkan', 'data'  =>  $barang, 'detail_order' => $detail], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 400, 'errors' => $e->getMessage()], 400);
    //     }
    // }


    public function store(Request $request)
    {
        $id_user = auth()->user()->id_user;
        $cart = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
        $cartz = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
        $checkcart = Cart::select('qty')->where('id_user', $id_user)->get();
        $lebih = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->select('qty')->where('id_user', $id_user)->get();
        $brg = Cart::join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->select('stok')->where('id_user', $id_user)->get();
        $validatorTransaksi = Validator::make($transaksi = $request->all(), [
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
            if ($checkcart == '[]') {
                return response()->json(['status' => 404, 'errors' => 'Keranjang kosong'], 404);
            }
            foreach ($cart as $cart) {
                if ($cart->qty > $cart->stok) {
                    try {
                        return response()->json(['status' => 422, 'message' => 'Melebihi Stok']);
                    } catch (\Throwable $th) {
                        return response()->json(['status' => 500, 'errors' => $th->getMessage()]);
                    }
                }
                $id_barang = $cart->id_barang;
                $detail = DetailOrder::create([
                    'no_order' => $transaksi['no_order'],
                    'id_user' => $id_user,
                    'id_barang' => $id_barang,
                    'qty' => $cart->qty
                ]);
                $carts = Barang::where('id_barang', $id_barang);
                $kurang = $cart->stok - $cart->qty;
                $carts->update(['stok' => $kurang]);
                $check = DB::table('carts')->where('id_user', $id_user)->where('id_barang', $id_barang);
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
            return response()->json(['status' => 200, 'meesage' => 'Transaksi berhasil ditambahkan', 'data' => $barang, 'detail_order' => $detail, $lebih, $brg, $lebih > $brg], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaksi = Transaksi::find($id);

        if ($transaksi != NULL) {
            return response()->json(['status' => 200, 'message' => 'Transaksi ditemukan', 'data' => $transaksi], 200);
        } else {
            return response()->json(['status' => 404, 'message' => 'Transaksi tidak ditemukan', 'data' => $transaksi], 404);
        }
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
        // $transaksi = Transaksi::find($id);

        // $transaksiGET = DB::table('transaksis')->join('detailorders', 'transaksis.no_order', '=', 'detailorders.no_order')->where('transaksis.id_transaksi', $id)->first();

        // $transaksiUPDATE = DB::table('transaksis')->join('detailorders', 'transaksis.no_order', '=', 'detailorders.no_order')->where('transaksis.id_transaksi', $id);

        // $detailUPDATE = DB::table('detailorders')->join('transaksis', 'detailorders.no_order', '=', 'transaksis.no_order')->where('transaksis.id_transaksi', '=', $id);

        // // $transaksi = Transaksi::where('no_order', $no_order);

        // $validatorTransaksi = Validator::make($request->all(), [


        //     'tanggal_order' => ['required'],
        //     'nama_penerima' => ['required'],
        //     'alamat' => ['required'],
        //     'telp_penerima' => ['required'],
        //     'total_berat' => ['required'],
        //     'ongkir' => ['required'],
        //     'total_bayar' => ['required'],
        //     'status_bayar' => ['required'],
        // ]);

        // $validatorDetail = Validator::make($request->all(), [

        //     'id_barang' => ['required'],
        //     'qty' => ['required'],

        // ]);

        // if (($transaksiGET != NULL && $validatorDetail->fails()) && $transaksiGET != NULL && $validatorTransaksi->fails()) {
        //     return response()->json(['status' => 422, 'errors' => $validatorTransaksi->errors(), 'detail_order' => $validatorDetail->errors()], 422);
        // } else if ($transaksiGET != NULL && $validatorTransaksi->fails()) {
        //     return response()->json(['status' => 422, 'errors' => $validatorTransaksi->errors()], 422);
        // } else if ($transaksiGET != NULL && $validatorDetail->fails()) {
        //     return response()->json(['status' => 422, 'errors' => ['detail_order' => $validatorDetail->errors()]], 422);
        // } else if ($transaksiGET != NULL) {
        //     $transaksiUPDATE->update([
        //         'tanggal_order' => $request->tanggal_order,
        //         'nama_penerima' => $request->nama_penerima,
        //         'alamat' => $request->alamat,
        //         'ongkir' => $request->ongkir,
        //         'telp_penerima' => $request->telp_penerima,
        //         'total_berat' => $request->total_berat,
        //         'total_bayar' => $request->total_bayar,
        //         'status_bayar' => $request->status_bayar,
        //     ]);


        //     $detailUPDATE->update([
        //         'id_barang' => $request->id_barang,
        //         'qty' => $request->qty,

        //     ]);


        //     return response()->json(['status' => 200, 'message' => 'Transaksi berhasil diperbarui', 'data' => $transaksiUPDATE->get()], 200);
        // } else {
        //     return response()->json(['status' => 404, 'message' => 'Transaksi tidak ditemukan'], 404);
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($no_order)
    {

        $transaksi = DB::table('transaksis')->where('no_order', $no_order)->get();
        $detail = DB::table('detailorders')->where('no_order', $no_order)->get();
        $transaksiDelete = DB::table('transaksis')->where('no_order', $no_order);
        $detailDelete = DB::table('detailorders')->where('no_order', $no_order);




        if ($transaksi == '[]' && $detail == '[]') {
            return response()->json(['status' => 500, 'message' => 'Transaksi gagal dihapus'], 500);
        } else {
            $transaksiDelete->delete();
            $detailDelete->delete();
            return response()->json(['status' => 200, 'message' => 'Transaksi berhasil dihapus'], 200);
        }
    }
}
