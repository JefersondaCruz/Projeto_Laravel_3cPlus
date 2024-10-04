<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\explorador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExploradorController extends Controller
{
    public function store(Request $request):RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string','max:255'],
            'idade' => ['required', 'string', 'max:3'],
            'latitude' => ['required', 'string','max:255'],
            'longitude' =>['required', 'string','max:255'],
        ]);

        explorador::create([
            'name'=> $request->name,
            'idade' => $request->idade,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json();
    }
}
