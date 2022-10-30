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

foreach ($cartz as $cartz) {
if ($checkcart != '[]' && $lebih > $brg == true) {
return response()->json(['status' => 422, 'meesage' => 'Melebihi stok']);
}
}

if ($checkcart != '[]') {
foreach ($cart as $cart) {
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
// $check->delete();
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
} else {
return response()->json(['status' => 400, 'message' => 'Keranjang Kosong', $lebih, $brg]);
}
} catch (\Throwable $th) {
return response()->json(['status' => 500, 'message' => $th->getMessage()]);
}


$id_user = auth()->user()->id_user;
$cart = DB::table('carts')->join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->get();
$cartz = DB::table('carts')->join('barangs', 'barangs.id_barang', '=', 'carts.id_barang')->where('id_user', $id_user)->count();


try {
foreach ($cart as $cart) {
for ($i = 1; $i < $cartz; $i++) { if ($cart->qty > $cart->stok) {
    return response()->json(['status' => 200, 'message' => 'Melebihi stok', $cart->qty == $cart->stok, $cartz]);
    } else {
    return response()->json(['status' => 200, 'message' => $cart, $cart->qty == $cart->stok]);
    }
    }
    }
    } catch (\Throwable $th) {
    //throw $th;
    }

    echo $cart[0]->qty;
    foreach ($cart as $cart) {
    return response()->json(['status' => 422, 'message' => 'Melebihi Stok', $cart, $cart->stok]);
    }