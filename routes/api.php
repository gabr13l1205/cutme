<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\KapsterController;
use App\Http\Controllers\JadwalKapsterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DetailBookingController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\AdminController;


// =====================================================
// USER
// =====================================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// =====================================================
// AUTH
// =====================================================

// Register pelanggan
Route::post(
    '/auth/register',
    [AuthController::class, 'register']
);

// Login pelanggan
Route::post(
    '/auth/login',
    [AuthController::class, 'login']
);


// =====================================================
// DATA LAYANAN
// =====================================================

// Daftar layanan untuk pelanggan
Route::get(
    '/layanan',
    [LayananController::class, 'index']
);


// =====================================================
// DATA KAPSTER
// =====================================================

// Daftar kapster untuk pelanggan
Route::get(
    '/kapster',
    [KapsterController::class, 'index']
);


// =====================================================
// JADWAL KAPSTER
// =====================================================

// Daftar jadwal kapster
Route::get(
    '/jadwal-kapster',
    [JadwalKapsterController::class, 'index']
);


// =====================================================
// BOOKING
// =====================================================

// Membuat booking
Route::post(
    '/booking',
    [BookingController::class, 'store']
);

// Riwayat booking pelanggan
Route::get(
    '/booking/history/{idPelanggan}',
    [BookingController::class, 'history']
);

// Daftar booking berdasarkan kapster
Route::get(
    '/booking/kapster/{idKapster}',
    [BookingController::class, 'kapsterBookings']
);

// Update status booking oleh kapster
Route::put(
    '/booking/status/{idBooking}',
    [BookingController::class, 'updateStatus']
);


// =====================================================
// DETAIL BOOKING
// =====================================================

// Menyimpan detail layanan booking
Route::post(
    '/detail-booking',
    [DetailBookingController::class, 'store']
);


// =====================================================
// PEMBAYARAN
// =====================================================

// Menyimpan pembayaran
Route::post(
    '/pembayaran',
    [PembayaranController::class, 'store']
);


// =====================================================
// ULASAN
// =====================================================

// Menambahkan ulasan
Route::post(
    '/ulasan',
    [UlasanController::class, 'store']
);

// Menampilkan ulasan pelanggan
Route::get(
    '/ulasan/{idPelanggan}',
    [UlasanController::class, 'index']
);


// =====================================================
// ADMIN / KASIR - KELOLA LAYANAN
// =====================================================

// Menampilkan semua layanan
Route::get(
    '/admin/layanan',
    [AdminController::class, 'layanan']
);

// Menambahkan layanan
Route::post(
    '/admin/layanan',
    [AdminController::class, 'tambahLayanan']
);

// Mengubah layanan
Route::put(
    '/admin/layanan/{id}',
    [AdminController::class, 'updateLayanan']
);

// Menghapus layanan
Route::delete(
    '/admin/layanan/{id}',
    [AdminController::class, 'hapusLayanan']
);


// =====================================================
// ADMIN / KASIR - KELOLA KAPSTER
// =====================================================

// Menampilkan semua kapster
Route::get(
    '/admin/kapster',
    [AdminController::class, 'kapster']
);

// Menambahkan kapster
Route::post(
    '/admin/kapster',
    [AdminController::class, 'tambahKapster']
);

// Mengubah kapster
Route::put(
    '/admin/kapster/{id}',
    [AdminController::class, 'updateKapster']
);

// Menghapus kapster
Route::delete(
    '/admin/kapster/{id}',
    [AdminController::class, 'hapusKapster']
);


// =====================================================
// ADMIN / KASIR - LAPORAN
// =====================================================

// Menampilkan laporan booking
Route::get(
    '/admin/laporan',
    [AdminController::class, 'laporan']
);