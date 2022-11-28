<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json(['status' => 200, 'message' => 'User berhasil ditampilkan', 'data' => $user]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'role' => 'required',
            'password' => ['required', 'required_with:confirm_password', 'same:confirm_password'],
            'confirm_password' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()]);
        }

        try {
            $users = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password)

            ]);

            return response()->json(['status' => 200, 'message' => 'User berhasil ditambahkan', 'data' => $users]);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()]);
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
        $user = User::find($id);


        if ($user == null) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan'], 404);
        }

        try {
            return response()->json(['status' => 200, 'message' => 'Data berhasil ditemukan', 'data' => $user], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => 500, 'message' => $th->getMessage()], 500);
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
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users'],
            'role' => 'required',
            'password' => ['required', 'required_with:confirm_password', 'same:confirm_password'],
            'confirm_password' => 'required'
        ]);


        if ($validator->fails() && $user == null) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan'], 404);
        } else if (!$validator->fails() && $user == null) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan'], 404);
        } else if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => $validator->errors()], 422);
        }
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => $request->role
            ]);
        } catch (\Throwable $th) {
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
        $user = User::find($id);

        if ($user == null) {
            return response()->json(['status' => 404, 'message' => 'User tidak ditemukan']);
        }

        try {
            $user->delete();
            return response()->json(['status' => 200, 'message' => 'User berhasil dihapus']);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
