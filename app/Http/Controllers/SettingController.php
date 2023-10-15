<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index() {
        $redirect_page = Setting::where('name', 'redirect_page')->first();
        return view('settings.index', [
            'redirect_page' => $redirect_page
        ]);
    }
}
