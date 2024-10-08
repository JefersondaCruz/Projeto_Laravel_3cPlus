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
        $troca = $request->validate([
            'explorador1_id' => ['required', 'exists:exploradors,id'],
            'explorador2_id' => ['required', 'exists:exploradors,id'],
            'item_explorador1' => ['required', 'exists:items,id'],
            'item_explorador2' => ['required', 'exists:items,id'],
        ]);

        $explorador1_id = explorador::find($request->explorador1_id);
        $explorador2_id = explorador::find($request->explorador2_id);
        $itemExplorador1 = Item::find($request->item_explorador1);
        $itemExplorador2 = Item::find($request->item_explorador2);



        if ($itemExplorador1->explorador_id !== $explorador1_id->id) {
            return response()->json(['error' => 'Item não pertence ao Explorador 1.'], );
        }
        if ($itemExplorador2->explorador_id !== $explorador2_id->id) {
            return response()->json(['error' => 'Item não pertence ao Explorador 2.'], );
        }


        if ($itemExplorador1->valor !== $itemExplorador2->valor) {
            return response()->json(['error' => 'Os items devem ter o mesmo valor.'], );
        }

        $itemExplorador1->explorador_id = $explorador2_id->id;
        $itemExplorador2->explorador_id = $explorador1_id->id;
        $itemExplorador1->save();
        $itemExplorador2->save();

        return response()->json([
            'message' => 'Trocado com sucesso!',
            'explorador1' => [
                'id' => $explorador1_id->id,
                'nome' => $explorador1_id->name,
                'item_trocado' => $itemExplorador1,
            ],
            'explorador2' => [
                'id' => $explorador2_id->id,
                'nome' => $explorador2_id->name,
                'item_trocado' => $itemExplorador2,
            ],
        ]);



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
            'inventario' => $explorador->item()
        ]);
    }
}
