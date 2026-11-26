<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:pelanggan,email',
            'password' => 'required|string|min:6',
        ]);

        $pelanggan = Pelanggan::create([
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'id_pelanggan' => $pelanggan->id_pelanggan,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $pelanggan = Pelanggan::where('email', $request->email)->first();

        if (!$pelanggan || !Hash::check($request->password, $pelanggan->password)) {
            return response()->json([
                'message' => 'Email atau password salah',
            ], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'nama' => $pelanggan->nama,
            'email' => $pelanggan->email,
        ]);
    }
}