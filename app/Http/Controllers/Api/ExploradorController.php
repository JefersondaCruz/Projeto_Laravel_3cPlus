<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\explorador;
use App\Models\item;
use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

class ExploradorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string','max:255'],
            'idade' => ['required', 'integer', 'max:122'],
            'latitude' => ['required', 'string','max:255'],
            'longitude' =>['required', 'string','max:255'],
            'inventario' => ['nullable', 'string','max:255'],

        ]);

        $inventario = $request->inventario ?? 'vazio';

        $explorador= explorador::create([
            'name'=> $request->name,
            'idade' => $request->idade,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'inventario' => $inventario,
        ]);

        return response()->json([
            'message'=> 'explorador criado com sucesso!',
            'explorador' => $explorador,
        ]);
    }


    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'string', 'max:255'],
            'longitude' => ['required', 'string', 'max:255'],
        ]);

        $explorador = explorador::findOrFail($id);

        $explorador->latitude = $request->latitude;
        $explorador->longitude = $request->longitude;
        $explorador->save();

        return response()->json([
            'message' => 'Localização atualizada',
            'explorador' => $explorador,
        ]);

    }

    public function adicionarItem(Request $request, $explorador_ID): JsonResponse
    {
        $request->validate([
            'nome' => ['required', 'string','max:255'],
            'valor' => ['required', 'numeric', 'max:122'],
            'latitude' => ['required', 'string','max:255'],
            'longitude' =>['required', 'string','max:255'],
        ]);

        $item = item::create([
            'explorador_id' => $explorador_ID,
            'nome' => $request->nome,
            'valor' => $request->valor,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

            return response()->json([
                'message' => 'item adicionado ao inventario',
                'item' => $item,
            ]);
    }

    public function trocarItems(Request $request): JsonResponse

    {

        
        $explorador1 = explorador::findOrFail($request->explorador1_id);
        $explorador2 = explorador::findOrFail($request->explorador2_id);

    }

    public function show(Request $request, $id): JsonResponse
    {
        $explorador = explorador::with('item')->find($id);

        if (!$explorador) {
            return response()->json([
                'message'=> 'Explorador nao encontrado'
            ]);
        }

        return response()->json([
            'explorador' => $explorador,
            'inventario' => $explorador->items()
        ]);
    }
}
