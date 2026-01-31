<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ClothingItem;

class OutfitController extends Controller
{
    public function suggest(Request $request)
    {
        $user = auth()->user();

        if (!$user->location_city) {
            return response()->json([
                'error' => 'User location city not set'
            ], 400);
        }

        $clothes = ClothingItem::where('user_id', $user->id)
            ->get(['id', 'category', 'color', 'image_url'])
            ->toArray();

        if (count($clothes) === 0) {
            return response()->json(['error' => 'Wardrobe is empty'], 400);
        }

        $response = Http::post(
            'http://127.0.0.1:8001/suggest-outfit',
            [
                'city' => $user->location_city,
                'country' => $user->location_country,
                'clothes' => $clothes
            ]
        );

        if ($response->failed()) {
            return response()->json([
                'error' => 'AI service failed',
                'details' => $response->body()
            ], 500);
        }

        return response()->json($response->json());
    }

}
