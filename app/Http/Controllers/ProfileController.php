<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class ProfileController extends Controller
{
    public function index() {
        $user   = User::where('id', auth()->id())->first();
        $token  = ApiKey::where('user_id', auth()->id())->first();
        $key    = $token ? $token->key : null;
        return view('profile.index', [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'token' => $key
        ]);           
    }

    public function editProfile(Request $request) {
        $id         = $request->id;
        $validate   = $request->validate([
            'name'  => 'required|max:100',
            'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($request->id)
            ],
        ]);
        if($validate) {
            User::where('id', $id)->update([
                'name'  => $request->name,
                'email' => $request->email
            ]);
            return response()->json([
                'success' => 'Profile Successfully Updated'
            ], 200);
        }
        else {
            return response()->json([
                'error' => 'Error Occured. Please contact site administrator!'
            ], 422);
        }
    }
    
    public function editPassword(Request $request) {
        $id         = $request->id;
        $validate   = $request->validate([
            'oldpassword'   => 'required',
            'newpassword'   => 'required',
            'newpassword2'  => 'required'
        ], [
            'oldpassword.required'  => 'Please fill your Old Password',
            'newpassword.required'  => 'Please fill your New Password',
            'newpassword2.required' => 'Please fill your Verify Password',
        ]);
        if($validate) {
            $user = auth()->user();
            if(Hash::check($request->oldpassword, $user->password)) {
                if($request->newpassword == $request->newpassword2) {
                    $user->update([
                        'password' => Hash::make($request->newpassword)
                    ]);
                    return response()->json([
                        'success' => 'Password successfully changed'
                    ], 200);
                }
                else {
                    return response()->json([
                        'message' => 'Error. New Password and Verify Password must be same!'
                    ], 422);
                }
            }
            else {
                return response()->json([
                    'message' => 'Error. Wrong Old Password!'
                ], 422);
            }
        }
        else {
            return response()->json([
                'message' => 'Error Occured. Please contact site administrator!'
            ], 422);
        }        
    }
}
