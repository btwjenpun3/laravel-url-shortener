<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Setting;

class RedirectUrlController extends Controller
{
    public function redirect(Request $request) {
        $url = Link::where('short_url', $request->url)->first(); 
        $redirect_page = Setting::where('name', 'redirect_page')->first();       
        if(isset($url->password)) {
            return view('password.index', [
                'id' => $url->id,
                'short_url' => $url->short_url
            ]);
        }
        elseif($redirect_page->value == 'true') {
            return view('redirect.index', [
                'link' => $url->original_url
            ]);
        }
        elseif($redirect_page->value == 'false') {
            return redirect($url->original_url);
        }
        else {
            abort(404);
        }
    }

    public function unlockPassword(Request $request) {
        $link = Link::where('id', $request->id)->first();
        if(password_verify($request->password, $link->password)) {
            return redirect($link->original_url);
        }
        else {
            return redirect()->route('redirect.index', ['url' => $link->short_url])->with(['message' => 'Credentials is not match to our record!']);
        }
    }
}
