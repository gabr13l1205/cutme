<?php

namespace App\Http\Controllers;

use App\Models\Kapster;

class KapsterController extends Controller
{
    public function index()
    {
        $kapster = Kapster::where('status_aktif', 1)
            ->orderBy('id_kapster', 'asc')
            ->get();

        return response()->json($kapster);
    }
}