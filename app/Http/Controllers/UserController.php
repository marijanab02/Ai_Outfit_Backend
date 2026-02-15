<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function update(Request $request, User $user)
    {
    
        $request->validate([
            'location_city' => 'required|string|max:255',
        ]);

        $user->update([
            'location_city' => $request->location_city,
        ]);

        return response()->json([
            'message' => 'Grad uspješno promijenjen',
            'user' => $user
        ]);
    }
}
