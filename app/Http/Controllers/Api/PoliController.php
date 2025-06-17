<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poli;

class PoliController extends Controller
{
    public function index()
    {
        return response()->json(Poli::all()); // ✅ Mengembalikan data poli langsung
    }

    public function show($id)
    {
        $poli = Poli::find($id);
        if (!$poli) {
            return response()->json(['message' => 'Poli tidak ditemukan'], 404);
        }
        return response()->json($poli);
    }
}
