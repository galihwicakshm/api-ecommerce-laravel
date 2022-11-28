<?php

namespace App\Services\Cart;


use Illuminate\Http\Request;
use App\Repositories\Barang\BarangRepository;
use LaravelEasyRepository\Service;
use App\Repositories\Cart\CartRepository;

class CartServiceImplement extends Service implements CartService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(CartRepository $cartRepository, BarangRepository $barangRepository)
  {
    $this->cartRepository = $cartRepository;
    $this->barangRepository = $barangRepository;
  }

  public function store(Request $request, $data)
  {
    $id_user = auth()->user()->id_user;
    $barang = $this->barangRepostory->findBarang($request->id_barang);
    if ($request->qty <= $barang['stok'] && $barang != null) {
      $cart = $this->cartRepository->joinBarangAuthID()->get();
      $carts = $this->cartRepository->joinBarangWhere()->get();
      if ($carts == '[]') {
        $total = $barang->harga * $request->qty;
        $cart = $this->cartRepository->store([
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
        $cartUpdate =
          $this->cartRepository->joinBarangWhere();
        $getCart = $cartUpdate->get();
        $updateQty = $getCart[0]->qty + $request->qty;
        $cartUpdate->update(['qty' => $updateQty]);
        $dataAfter =
          $this->cartRepository->joinBarangWhere()->get();
        return response()->json(['status' => 200, 'message' => 'Cart berhasil ditambahkan', 'data' =>  $dataAfter], 200);
      }
    } else if (($request->qty > $barang['stok'] && $barang != null)) {
      return response()->json(['status' => 422, 'message' => 'Melebihi stok'], 422);
    } else {
      return response()->json(['status' => 404, 'errors' => 'Barang tidak ditemukan'], 404);
    }
  }
}
