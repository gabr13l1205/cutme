<?php

namespace App\Http\Controllers;

use App\Models\DetailBooking;
use App\Models\Layanan;
use Illuminate\Http\Request;

class DetailBookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|integer',
            'id_layanan' => 'required|integer',
        ]);

        $layanan = Layanan::find($request->id_layanan);

        if (!$layanan) {
            return response()->json([
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        $detail = DetailBooking::create([
            'id_booking' => $request->id_booking,
            'id_layanan' => $request->id_layanan,
            'subtotal' => $layanan->harga,
        ]);

        return response()->json([
            'message' => 'Detail booking berhasil ditambahkan',
            'data' => $detail,
        ], 201);
    }
}