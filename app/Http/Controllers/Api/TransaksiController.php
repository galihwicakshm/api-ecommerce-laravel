<?php

namespace App\Http\Controllers\Api;

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
        $transaksi = DB::table('transaksis')->get();


        // $transaksi = DB::table('transaksis');

        return response()->json(['status' => 200, "message" => "Transaksi berhasil ditampilkan", "data" => $transaksi]);
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
    public function store(Request $request)
    {

        $id_user = auth()->user()->id_user;

        // var_dump(auth()->user()->id_user);
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
            'id_barang' => ['required'],
            'qty' => ['required'],

        ]);


        if ($validatorDetail->fails() && $validatorTransaksi->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors(), 'detail_order' => $validatorDetail->errors()], 400);
        } else if ($validatorDetail->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorDetail->errors()], 400);
        } else if ($validatorTransaksi->fails()) {
            return response()->json(['status' => 400, 'errors' => $validatorTransaksi->errors()], 400);
        }

        try {
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


            $detail = DetailOrder::create([
                'no_order' => $transaksi['no_order'],
                'id_user' =>  $id_user,
                'id_barang' => $request->id_barang,
                'qty' => $request->qty,

            ]);

            return response()->json(['status' => 200, 'meesage' => 'Transaksi berhasil ditambahkan', 'data'  =>  $barang, 'detail_order' => $detail], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()], 400);
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
            return response()->json(['status' => 400, 'message' => 'Transaksi gagal dihapus'], 400);
        } else {
            $transaksiDelete->delete();
            $detailDelete->delete();
            return response()->json(['status' => 200, 'message' => 'Transaksi berhasil dihapus'], 200);
        }
    }
}
