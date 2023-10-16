<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class ProfileController extends Controller
{
    public function index() {
        $token = ApiKey::where('user_id', auth()->id())->first();
        if($token) {
            return view('profile.index', [
                'token' => $token->key
            ]);
        } else {
            return view('profile.index');
        }        
    }
}
