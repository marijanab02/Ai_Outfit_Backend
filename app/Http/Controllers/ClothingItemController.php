<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\ClothingItem;

use Illuminate\Http\Request;

class ClothingItemController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Dohvati sve odjevne predmete korisnika, grupirano po kategoriji
        $items = ClothingItem::where('user_id', $userId)
            ->orderBy('category')
            ->get()
            ->groupBy('category');

        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'name' => 'nullable|string'
        ]);

        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image found in request'], 400);
        }

        $path = $request->file('image')->store('clothes', 'public');

        // 2️⃣ pošalji AI servisu
        $response = Http::attach(
            'file',
            Storage::disk('public')->get($path),
            basename($path)
        )->post('http://127.0.0.1:8001/analyze');

        $ai = $response->json();
        if (!isset($ai['category']['category'], $ai['color']['hex'])) {
            return response()->json(['error' => 'AI servis vratio neispravan format'], 500);
        }
        // 3️⃣ spremi u bazu
        $item = ClothingItem::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'category' => $ai['category']['category'],
            'color' => $ai['color']['hex'],
            'image_url' => $path
        ]);

        return response()->json($item, 201);
    }


    public function analyze(Request $request)
    {
        $request->validate([
            'image' => 'required|image'
        ]);

        $path = $request->file('image')->store('clothes', 'public');

        $response = Http::attach(
            'file',
            Storage::disk('public')->get($path),
            basename($path)
        )->post('http://127.0.0.1:8001/analyze');

        $data = $response->json();

        // spremi u bazu
        auth()->user()->clothingItems()->create([
            'category' => $data['category']['category'],
            'color' => $data['color']['hex'],
            'image_url' => $path
        ]);

        return response()->json($data);
    }
    public function destroy($id)
    {
        $item = ClothingItem::where('user_id', auth()->id())->find($id);

        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Obriši sliku iz storage-a
        if ($item->image_url) {
            $path = str_replace(url('/storage').'/', '', $item->image_url); 
            Storage::disk('public')->delete($path);
        }

        // Obriši zapis iz baze
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
