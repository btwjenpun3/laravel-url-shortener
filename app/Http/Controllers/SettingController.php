<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Gate;

class SettingController extends Controller
{
    public function index() {
        if (Gate::allows('admin-only')) {
            $redirect_page = Setting::where('name', 'redirect_page')->first();
            return view('settings.index', [
                'redirect_page' => $redirect_page
            ]);
        } else {
            abort(404);
        }
        
    }

    public function update(Request $request) {
        if (Gate::allows('admin-only')) {
            Setting::where('name', 'redirect_page')->update([
                'value' => $request->redirect_page
            ]);
            return response()->json([
                'success' => 'Setting updated.'
            ]);
        } else {
            abort(404);
        }        
    }
}
