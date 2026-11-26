<?php

namespace App\Http\Controllers;

use App\Models\JadwalKapster;
use Illuminate\Http\Request;

class JadwalKapsterController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalKapster::query();

        if ($request->has('id_kapster')) {
            $query->where('id_kapster', $request->id_kapster);
        }

        if ($request->has('tanggal')) {
            $query->where('tanggal', $request->tanggal);
        }

        $jadwal = $query
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();

        return response()->json($jadwal);
    }
}