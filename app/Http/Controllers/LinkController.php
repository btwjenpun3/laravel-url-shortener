<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class LinkController extends Controller
{
    public function index() {
        $links = Link::latest()->paginate(5);
        return view('link.index', [
            'links' => $links
        ]);
    }

    public function store(Request $request) {
        $validate = $request->validate([
            'title' => 'max:100',
            'link' => 'required'
        ]);
        if($validate) {
            $time = Carbon::now();
            $crypt = Crypt::encryptString($time);
            Link::create([
                'user_id' => 1,
                'title' => $request->title,
                'original_url' => $request->link,
                'short_url' => substr($crypt, 30, 6),
                'status' => true
            ]);
            return response()->json([
                'success' => 'Link successfuly created.'
            ]);
        }
    }

    public function edit(Request $request) {
        $validate = $request->validate([
            'title' => 'max:100',
            'short_url' => 'required|max:15',
            'original_url' => 'required'
        ]);
        if($validate){
            Link::where('id', $request->id)->update([
                'title' => $request->title,
                'short_url' => $request->short_url,
                'original_url' => $request->original_url
            ]);
            return response()->json([
                'success' => 'Shortlink successfully edited'
            ]);
        }
        return response()->json();
    }

    public function destroy(Request $request) {        
        Link::where('id', $request->id)->delete();
        return response()->json([
            'code' => 200
        ], 200);        
    }
}
