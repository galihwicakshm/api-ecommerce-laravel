<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Barang\BarangRepository;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{


    public $barangRepository;

    public function __construct(BarangRepository $barangRepository)
    {
        $this->barangRepository = $barangRepository;
    }


    public function index()
    {

        $barang = $this->barangRepository->getAll();
        return $barang;
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
     *
    
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kategori' => ['required'],
            'nama_barang' => ['required'],
            'berat' => ['required'],
            'stok' => ['required'],
            'harga' => ['required'],
            'deskripsi' => ['required'],
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        }

        try {
            $barang = Barang::create($request->all());
            return response()->json(['status' => 200, 'message' => 'Barang berhasil ditambahkan', 'data' => $barang]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Barang gagal ditambahkan', ' errors' => $th->getMessage()], 500);
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

        $barang = $this->barangRepository->cariBarang($id);
        if ($barang != '[]') {
            return response()->json(['status' => 200, 'message' => 'Barang berhasil ditampilkan', 'data' => $barang]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Barang tidak ditemukan'], 404);
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


        $barang = $this->barangRepository->findBarang($id);
        $validator = Validator::make($request->all(), [
            'id_kategori' => ['required'],
            'nama_barang' => ['required'],
            'berat' => ['required'],
            'stok' => ['required'],
            'harga' => ['required'],
            'deskripsi' => ['required'],
        ]);

        if ($barang != NULL && $validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        } else if ($barang != NULL) {
            $barang->update($request->all());
            return response()->json(['status' => 200, 'message' => 'Barang berhasil diperbarui', 'data' => $barang], 404);
        } else {
            return response()->json(['status' => 404, 'message' => 'Barang tidak ditemukan'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang = $this->barangRepository->findBarang($id);

        try {
            $barang->delete();
            return response()->json(['status' => 200, 'message' => 'Barang berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Barang gagal dihapus'], 500);
        }
    }
}
