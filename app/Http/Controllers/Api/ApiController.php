<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\ApiKey;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;

class ApiController extends Controller
{
    public function generate() {
        $user = auth()->user();
        $token_name = 'API-Token';
        $token = $user->createToken($token_name);
        ApiKey::create([
            'user_id' => auth()->id(),
            'key' => $token->plainTextToken
        ]);
        return response()->json([
            'success' => 'API Key successfully created.'
        ]);
    }    

    public function get() {
        $link = Link::where('user_id', auth()->id())->first();
        return response()->json([
            'code' => 200,
            'link' => $link
        ], 200);
    }
}
