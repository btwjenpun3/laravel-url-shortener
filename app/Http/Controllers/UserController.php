<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use App\DataTables\UsersDataTable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return view('users.index');
    }

    public function userDataTable() {
        $users = User::select(['id', 'name', 'email', 'created_at']);
        return DataTables::of($users)->toJson();
    }
}
