<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
            'link' => 'required|url'
        ]);
        if($validate) {
            $time = Carbon::now();
            $crypt = Crypt::encryptString($time);
            $data = [
                'user_id' => 1,
                'title' => $request->title,
                'original_url' => $request->link,
                'short_url' => substr($crypt, 30, 6),
                'status' => true
            ];
            if($request->filled('password')) {
                $data['password'] = $request->password;
            }
            Link::create($data);
            return response()->json([
                'success' => 'Link successfuly created.'
            ]);
        }
    }

    public function edit(Request $request) {
        $validate = $request->validate([
            'title' => 'max:100',
            'short_url' => 'required|max:100|regex:/^[A-Za-z0-9-]+$/|unique:links,short_url',
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
    }

    public function password(Request $request) {
        $validate = $request->validate([
            'password' => 'max:255|required'
        ]);
        if($validate) {
            Link::where('id', $request->id)->update([
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'success' => 'Password successfully set'
            ]);
        }       
    }

    public function removePassword(Request $request) {
        Link::where('id', $request->id)->update([
            'password' => null
        ]);
        return response()->json([
            'success' => 'Password successfully remove'
        ]);
    }

    public function destroy(Request $request) {        
        Link::where('id', $request->id)->delete();
        return response()->json([
            'code' => 200
        ], 200);        
    }

    public function download(Request $request) {
        $qrCode = QrCode::size(300)->format('svg')->generate(env('APP_URL') . '/' . $request->url);
        return $qrCode;
    } 
}
