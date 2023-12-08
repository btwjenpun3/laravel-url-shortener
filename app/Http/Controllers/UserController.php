<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ApiKey;
use DataTables;
use App\DataTables\UsersDataTable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return view('users.index');
    }

    public function userDataTable() {
        $users = User::select(['id', 'name', 'email', 'created_at', 'role_id', 'status']);
        return DataTables::of($users)->toJson();
    }

    public function detail(Request $request) {
        $id = $request->id;
        return view('users.detail', [
            'detail' => User::where('id', $id)->first(),
            'token' => ApiKey::where('user_id', $id)->pluck('key')->first()
        ]);
    }

    public function editUser(Request $request) {
        $id         = $request->id;
        $validate   = $request->validate([
            'role'      => 'required',
            'status'    => 'required'
        ]);
        if($validate){
            User::where('id', $id)->update([
                'role_id'   => $request->role,
                'status'    => $request->status
            ]);
            return response()->json([
                'success' => 'User successfully edited'
            ], 200);
        }
    }

    public function delete(Request $request) {
        $id     = $request->id;
        $user   = User::find($id);    
        if ($user) {
            ApiKey::where('user_id', $id)->delete();
            $user->tokens()->delete();
            $user->delete();
        }
    }
}
