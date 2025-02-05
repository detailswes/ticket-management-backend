<?php

namespace App\Http\Controllers;

use App\Http\Requests\TokenRequest;
use App\Models\User;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function generateToken(TokenRequest $request)
    {
        $user = new User();
        $user->id = Str::uuid();
        $user->name = $request->input('name');

        $token = $user->createToken($request->name);
        return response()->json(['token' => $token->plainTextToken], 201);

    }
}
