<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poli;

class PoliController extends Controller
{
    public function index()
    {
        return Poli::all();
    }

    public function show($id)
    {
        $poli = Poli::findOrFail($id);
        return response()->json(['data' => $poli]);
    }
}


    //

