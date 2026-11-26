<?php

namespace App\Http\Controllers;

use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::orderBy('id_layanan', 'asc')->get();

        return response()->json($layanan);
    }
}