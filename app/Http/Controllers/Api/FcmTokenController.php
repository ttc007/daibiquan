<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FcmToken;

class FcmTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string|unique:fcm_tokens,token',
        ]);

        FcmToken::updateOrCreate([
            'user_id' => 1, // nếu có login
            'token' => $request->token,
        ]);

        return response()->json(['message' => 'Token saved']);
    }
}
