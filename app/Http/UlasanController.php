<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|integer',
            'id_pelanggan' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string',
        ]);

        // Cek apakah booking sudah pernah diberi ulasan
        $sudahAda = Ulasan::where('id_booking', $request->id_booking)
            ->where('id_pelanggan', $request->id_pelanggan)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'message' => 'Booking ini sudah diberi ulasan',
            ], 409);
        }

        $ulasan = Ulasan::create([
            'id_booking' => $request->id_booking,
            'id_pelanggan' => $request->id_pelanggan,
            'rating' => $request->rating,
            'komentar' => $request->komentar,
            'tgl_ulasan' => now(),
        ]);

        return response()->json([
            'message' => 'Ulasan berhasil disimpan',
            'data' => $ulasan,
        ], 201);
    }

    public function index($idPelanggan)
    {
        $ulasan = Ulasan::where('id_pelanggan', $idPelanggan)
            ->orderBy('tgl_ulasan', 'desc')
            ->get();

        return response()->json([
            'data' => $ulasan,
        ]);
    }
}