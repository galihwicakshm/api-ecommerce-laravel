<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Barang\BarangService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Barang\BarangRepository;

class BarangController extends Controller
{


    public $barangRepository;
    public $barangService;

    public function __construct(BarangRepository $barangRepository, BarangService $barangService)
    {
        $this->barangRepository = $barangRepository;
        $this->barangService = $barangService;
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
            'gambar_name' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()], 422);
        }

        try {
            $barang = $this->barangService->store($request);
            return response()->json(['status' => 200, 'message' => 'Barang berhasil ditambahkan', 'data' => $barang]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500,  ' errors' => $th->getMessage()], 500);
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

        $barang = $this->barangRepository->findBarangKategori($id);
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
            'gambar_name' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($barang != NULL && $validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()], 400);
        } else if ($barang != NULL) {
            if ($barang->gambar_name) {
                Storage::delete($barang->gambar_name);
            }
            $gambar = $request->file('gambar_name')->store('gambar');
            $barang->update([
                'id_kategori' => $request->id_kategori,
                'nama_barang' => $request->nama_barang,
                'berat' => $request->berat,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'gambar_name' => $gambar,
            ]);
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
            Storage::delete($barang->gambar_name);
            return response()->json(['status' => 200, 'message' => 'Barang berhasil dihapus'], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => 'Barang gagal dihapus'], 500);
        }
    }
}
