<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ClothingItem;
use Carbon\Carbon;
use App\Models\Outfit;

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

public function store(Request $request)
    {
        $user = auth()->user();
        \Log::info($request->all());

        $request->validate([
            'items' => 'required|array|min:2',
            'items.*' => 'required|integer|exists:clothing_items,id',
            'temperature' => 'nullable|numeric',
        ]);

        // (opcionalno) zabrani više outfita isti dan
        $today = Carbon::today();

        $existing = Outfit::where('user_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existing) {
            return response()->json([
                'error' => 'Outfit for today already exists'
            ], 409);
        }

        $outfit = Outfit::create([
            'user_id' => $user->id,
            'weather_temp' => round($request->temperature),
        ]);

        $outfit->items()->attach($request->items);

        return response()->json([
            'message' => 'Outfit saved',
            'outfit_id' => $outfit->id
        ]);
    }
    public function index()
    {
        $user = auth()->user();

        $outfits = Outfit::where('user_id', $user->id)
            ->with([
                'items:id,name,category,color,image_url'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($outfits);
    }

}
