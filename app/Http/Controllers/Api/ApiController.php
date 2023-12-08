<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\User;
use App\Models\ApiKey;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;

class ApiController extends Controller
{
    public function generate(Request $request) {
        $id         = $request->id;
        $user       = User::where('id', $id)->first(); 
        $token_name = 'API-Token';
        $token      = $user->createToken($token_name);
        ApiKey::create([
            'user_id' => $id,
            'key' => $token->plainTextToken
        ]);
        return response()->json([
            'success' => 'API Key successfully created.'
        ], 200);
    }    

    public function regenerate(Request $request) {
        $id             = $request->id;
        $user           = User::where('id', $id)->first();          
        $token_name     = 'API-Token'; 
        $user->tokens()->delete();                 
        $token          = $user->createToken($token_name);        
        ApiKey::where('user_id', $id)->update([
            'key' => $token->plainTextToken
        ]);
        return response()->json([
            'success' => 'API Key successfully regenerated.'
        ], 200);
    }

    public function revoke(Request $request) {
        $id     = $request->id;
        $user   = User::where('id', $id)->first();
        $revoke = $user->tokens()->delete();
        if($revoke) {
            ApiKey::where('user_id', $id)->delete();
        }
        return response()->json([
            'success' => 'API Key successfully revoked.'
        ], 200);
    }

    public function get() {
        $link = Link::where('user_id', auth()->id())->first();
        return response()->json([
            'code' => 200,
            'link' => $link
        ], 200);
    }
}
