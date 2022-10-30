<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 200, 'message' => "View tampilan transaksi"]);
    }
}
