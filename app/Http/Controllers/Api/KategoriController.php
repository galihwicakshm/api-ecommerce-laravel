<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategori = Kategori::all();
        return response()->json(['status' => 200, 'message' => 'Kategori berhasil ditampilkan', 'data' => $kategori]);
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
        $validator = Validator::make($request->all(), [
            'nama_kategori' => ['required']
        ]);

        try {
            $kategori = Kategori::create($request->all());
            return response()->json(['status' => 200, 'message' => 'Kategori berhasil ditambahkan', 'data' => $kategori]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 422, 'message' => 'Kategori gagal ditambahkan', 'errors' => $validator->errors()], 422);
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

        $kategori = Kategori::find($id);


        if ($kategori != NULL) {
            return response()->json(['status' => 200, 'message' => 'Kategori berhasil ditampilkan', 'data' => $kategori]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan'], 404);
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
        $kategori = Kategori::find($id);

        $validator = Validator::make($request->all(), [
            'nama_kategori' => ['required']
        ]);

        if ($kategori != NULL && $validator->fails()) {
            return response()->json(['status' => 422, 'message' => 'Kategori gagal diperbarui', 'errors' => $validator->errors()], 422);
        } else if ($kategori != NULL) {
            $kategori->update($request->all());
            return response()->json(['status' => 200, 'message' => 'Kategori berhasil diperbarui', 'data' => $kategori]);
        } else {
            return response()->json(['status' => 404, 'message' => 'Kategori tidak ditemukan'], 404);
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
        $kategori = Kategori::find($id);

        try {
            $kategori->delete();
            return response()->json(['status' => 200, 'message' => 'Data berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['status' => 400, 'message' => 'Data gagal dihapus']);
        }
    }
}
