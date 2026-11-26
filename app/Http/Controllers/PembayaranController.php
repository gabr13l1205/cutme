<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_booking' => 'required|integer',
            'metode' => 'required|string|max:20',
            'jumlah_bayar' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Cari booking
            $booking = Booking::where(
                'id_booking',
                $request->id_booking
            )
                ->lockForUpdate()
                ->first();

            // Booking tidak ditemukan
            if (!$booking) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Booking tidak ditemukan',
                ], 404);
            }

            // Cek apakah booking sudah pernah dibayar
            $sudahBayar = Pembayaran::where(
                'id_booking',
                $request->id_booking
            )
                ->where(
                    'status_bayar',
                    'lunas'
                )
                ->exists();

            if ($sudahBayar) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Booking ini sudah dibayar',
                ], 409);
            }

            // Buat pembayaran
            $pembayaran = Pembayaran::create([
                'id_booking' => $request->id_booking,
                'metode' => $request->metode,
                'jumlah_bayar' => $request->jumlah_bayar,
                'tgl_bayar' => now(),
                'status_bayar' => 'lunas',
            ]);

            // Ubah status booking setelah pembayaran berhasil
            $booking->status = 'dibayar';
            $booking->save();

            DB::commit();

            return response()->json([
                'message' => 'Pembayaran berhasil',
                'data' => $pembayaran,
                'booking' => $booking,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Pembayaran gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}