<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\JadwalKapster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Membuat booking baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|integer',
            'id_kapster' => 'required|integer',
            'id_jadwal' => 'required|integer',
            'total_biaya' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $jadwal = JadwalKapster::where(
                'id_jadwal',
                $request->id_jadwal
            )
                ->where(
                    'id_kapster',
                    $request->id_kapster
                )
                ->lockForUpdate()
                ->first();

            if (!$jadwal) {
                DB::rollBack();

                return response()->json([
                    'message' => 'Jadwal tidak ditemukan',
                ], 404);
            }

            if ($jadwal->status_slot !== 'tersedia') {
                DB::rollBack();

                return response()->json([
                    'message' => 'Jadwal sudah tidak tersedia',
                ], 409);
            }

            $booking = Booking::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_kapster' => $request->id_kapster,
                'id_jadwal' => $request->id_jadwal,
                'tgl_booking' => now(),
                'status' => 'menunggu',
                'total_biaya' => $request->total_biaya,
            ]);

            $jadwal->update([
                'status_slot' => 'dibooking',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Booking berhasil',
                'data' => $booking,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Booking gagal',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengambil riwayat booking pelanggan
     */
    public function history($idPelanggan)
    {
        $pembayaranTerakhir = DB::table('pembayaran as p1')
            ->select(
                'p1.id_bayar',
                'p1.id_booking',
                'p1.metode',
                'p1.jumlah_bayar',
                'p1.status_bayar'
            )
            ->whereRaw('
                p1.id_bayar = (
                    SELECT MAX(p2.id_bayar)
                    FROM pembayaran as p2
                    WHERE p2.id_booking = p1.id_booking
                )
            ');

        $data = DB::table('booking')
            ->join(
                'kapster',
                'booking.id_kapster',
                '=',
                'kapster.id_kapster'
            )
            ->join(
                'jadwal_kapster',
                'booking.id_jadwal',
                '=',
                'jadwal_kapster.id_jadwal'
            )
            ->leftJoin(
                'detail_booking',
                'booking.id_booking',
                '=',
                'detail_booking.id_booking'
            )
            ->leftJoin(
                'layanan',
                'detail_booking.id_layanan',
                '=',
                'layanan.id_layanan'
            )
            ->leftJoinSub(
                $pembayaranTerakhir,
                'pembayaran',
                function ($join) {
                    $join->on(
                        'booking.id_booking',
                        '=',
                        'pembayaran.id_booking'
                    );
                }
            )
            ->where(
                'booking.id_pelanggan',
                $idPelanggan
            )
            ->select(
                'booking.id_booking',
                'booking.tgl_booking',
                'booking.status',
                'booking.total_biaya',

                'kapster.nama as nama_kapster',
                'kapster.spesialisasi',

                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai',
                'jadwal_kapster.jam_selesai',

                DB::raw(
                    "GROUP_CONCAT(
                        DISTINCT layanan.nama_layanan
                        SEPARATOR ', '
                    ) as nama_layanan"
                ),

                'pembayaran.metode',
                'pembayaran.jumlah_bayar',
                'pembayaran.status_bayar'
            )
            ->groupBy(
                'booking.id_booking',
                'booking.tgl_booking',
                'booking.status',
                'booking.total_biaya',

                'kapster.nama',
                'kapster.spesialisasi',

                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai',
                'jadwal_kapster.jam_selesai',

                'pembayaran.id_bayar',
                'pembayaran.id_booking',
                'pembayaran.metode',
                'pembayaran.jumlah_bayar',
                'pembayaran.status_bayar'
            )
            ->orderBy(
                'booking.id_booking',
                'desc'
            )
            ->get();

        return response()->json($data);
    }

    /**
     * Melihat booking berdasarkan kapster
     */
    public function kapsterBookings($idKapster)
    {
        $data = DB::table('booking')
            ->join(
                'pelanggan',
                'booking.id_pelanggan',
                '=',
                'pelanggan.id_pelanggan'
            )
            ->join(
                'jadwal_kapster',
                'booking.id_jadwal',
                '=',
                'jadwal_kapster.id_jadwal'
            )
            ->leftJoin(
                'detail_booking',
                'booking.id_booking',
                '=',
                'detail_booking.id_booking'
            )
            ->leftJoin(
                'layanan',
                'detail_booking.id_layanan',
                '=',
                'layanan.id_layanan'
            )
            ->where(
                'booking.id_kapster',
                $idKapster
            )
            ->select(
                'booking.id_booking',
                'booking.id_pelanggan',
                'pelanggan.nama as nama_pelanggan',
                'pelanggan.no_hp',

                'booking.id_kapster',
                'booking.id_jadwal',
                'booking.tgl_booking',
                'booking.status',
                'booking.total_biaya',

                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai',
                'jadwal_kapster.jam_selesai',

                DB::raw(
                    "GROUP_CONCAT(
                        DISTINCT layanan.nama_layanan
                        SEPARATOR ', '
                    ) as nama_layanan"
                )
            )
            ->groupBy(
                'booking.id_booking',
                'booking.id_pelanggan',
                'pelanggan.nama',
                'pelanggan.no_hp',

                'booking.id_kapster',
                'booking.id_jadwal',
                'booking.tgl_booking',
                'booking.status',
                'booking.total_biaya',

                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai',
                'jadwal_kapster.jam_selesai'
            )
            ->orderBy(
                'jadwal_kapster.tanggal',
                'asc'
            )
            ->orderBy(
                'jadwal_kapster.jam_mulai',
                'asc'
            )
            ->get();

        return response()->json($data);
    }

    /**
     * Mengubah status booking oleh kapster
     */
    public function updateStatus(Request $request, $idBooking)
    {
        $request->validate([
            'status' => 'required|string|in:datang,sedang_dilayani,selesai',
        ]);

        $booking = Booking::find($idBooking);

        if (!$booking) {
            return response()->json([
                'message' => 'Booking tidak ditemukan',
            ], 404);
        }

        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'message' => 'Status booking berhasil diperbarui',
            'data' => $booking,
        ], 200);
    }
}