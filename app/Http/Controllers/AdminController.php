<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // =====================================================
    // ADMIN / KASIR - KELOLA LAYANAN
    // =====================================================

    public function layanan()
    {
        $data = DB::table('layanan')
            ->orderBy('id_layanan', 'asc')
            ->get();

        return response()->json($data);
    }

    public function tambahLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'durasi_menit' => 'required|integer|min:1',
        ]);

        $id = DB::table('layanan')->insertGetId([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'durasi_menit' => $request->durasi_menit,
        ]);

        $data = DB::table('layanan')
            ->where('id_layanan', $id)
            ->first();

        return response()->json([
            'message' => 'Layanan berhasil ditambahkan',
            'data' => $data,
        ], 201);
    }

    public function updateLayanan(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'durasi_menit' => 'required|integer|min:1',
        ]);

        $cek = DB::table('layanan')
            ->where('id_layanan', $id)
            ->first();

        if (!$cek) {
            return response()->json([
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        DB::table('layanan')
            ->where('id_layanan', $id)
            ->update([
                'nama_layanan' => $request->nama_layanan,
                'harga' => $request->harga,
                'durasi_menit' => $request->durasi_menit,
            ]);

        $data = DB::table('layanan')
            ->where('id_layanan', $id)
            ->first();

        return response()->json([
            'message' => 'Layanan berhasil diperbarui',
            'data' => $data,
        ]);
    }

    public function hapusLayanan($id)
    {
        $cek = DB::table('layanan')
            ->where('id_layanan', $id)
            ->first();

        if (!$cek) {
            return response()->json([
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        try {
            DB::table('layanan')
                ->where('id_layanan', $id)
                ->delete();

            return response()->json([
                'message' => 'Layanan berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' =>
                    'Layanan tidak dapat dihapus karena masih digunakan oleh data lain.',
            ], 409);
        }
    }


    // =====================================================
    // ADMIN / KASIR - KELOLA KAPSTER
    // =====================================================

    public function kapster()
    {
        $data = DB::table('kapster')
            ->orderBy('id_kapster', 'asc')
            ->get();

        return response()->json($data);
    }

    public function tambahKapster(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'spesialisasi' => 'required|string|max:100',
            'status_aktif' => 'required|boolean',
        ]);

        $id = DB::table('kapster')->insertGetId([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'spesialisasi' => $request->spesialisasi,
            'status_aktif' => $request->status_aktif,
        ]);

        $data = DB::table('kapster')
            ->where('id_kapster', $id)
            ->first();

        return response()->json([
            'message' => 'Kapster berhasil ditambahkan',
            'data' => $data,
        ], 201);
    }

    public function updateKapster(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'spesialisasi' => 'required|string|max:100',
            'status_aktif' => 'required|boolean',
        ]);

        $cek = DB::table('kapster')
            ->where('id_kapster', $id)
            ->first();

        if (!$cek) {
            return response()->json([
                'message' => 'Kapster tidak ditemukan',
            ], 404);
        }

        DB::table('kapster')
            ->where('id_kapster', $id)
            ->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp,
                'spesialisasi' => $request->spesialisasi,
                'status_aktif' => $request->status_aktif,
            ]);

        $data = DB::table('kapster')
            ->where('id_kapster', $id)
            ->first();

        return response()->json([
            'message' => 'Kapster berhasil diperbarui',
            'data' => $data,
        ]);
    }

    public function hapusKapster($id)
    {
        $cek = DB::table('kapster')
            ->where('id_kapster', $id)
            ->first();

        if (!$cek) {
            return response()->json([
                'message' => 'Kapster tidak ditemukan',
            ], 404);
        }

        try {
            DB::table('kapster')
                ->where('id_kapster', $id)
                ->delete();

            return response()->json([
                'message' => 'Kapster berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' =>
                    'Kapster tidak dapat dihapus karena masih digunakan oleh data lain. Gunakan status nonaktif.',
            ], 409);
        }
    }


    // =====================================================
    // ADMIN / KASIR - LAPORAN BOOKING
    // =====================================================

    public function laporan()
    {
        $data = DB::table('booking')
            ->join(
                'pelanggan',
                'booking.id_pelanggan',
                '=',
                'pelanggan.id_pelanggan'
            )
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
            ->leftJoin(
                'pembayaran',
                'booking.id_booking',
                '=',
                'pembayaran.id_booking'
            )
            ->select(
                'booking.id_booking',
                'booking.tgl_booking',
                'pelanggan.nama as nama_pelanggan',
                'kapster.nama as nama_kapster',
                DB::raw(
                    "GROUP_CONCAT(
                        DISTINCT layanan.nama_layanan
                        ORDER BY layanan.id_layanan
                        SEPARATOR ' + '
                    ) as nama_layanan"
                ),
                'booking.total_biaya',
                'booking.status',
                'pembayaran.status_bayar',
                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai'
            )
            ->groupBy(
                'booking.id_booking',
                'booking.tgl_booking',
                'pelanggan.nama',
                'kapster.nama',
                'booking.total_biaya',
                'booking.status',
                'pembayaran.status_bayar',
                'jadwal_kapster.tanggal',
                'jadwal_kapster.jam_mulai'
            )
            ->orderBy(
                'jadwal_kapster.tanggal',
                'desc'
            )
            ->orderBy(
                'jadwal_kapster.jam_mulai',
                'desc'
            )
            ->get();

        return response()->json($data);
    }
}