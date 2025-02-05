<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function generateToken(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $name = $request->input('name');

            $user = new User();
            $user->id = Str::uuid();
            $user->name = $name;

            $token = $user->createToken($request->name);
            return response()->json(['token' => $token->plainTextToken], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate token.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
