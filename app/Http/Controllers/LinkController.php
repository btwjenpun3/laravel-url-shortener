<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LinkController extends Controller
{
    public function index() {
        $links = Link::where('user_id', auth()->id())->latest()->paginate(5);
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
                'user_id' => auth()->id(),
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
            'title'     => 'max:20',
            'short_url' => [
                    'required',
                    'max:20',
                    Rule::unique('links', 'short_url')->ignore($request->id),
                    'regex:/^[A-Za-z0-9-]+$/'
            ],
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
            ], 200);
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
            ], 200);
        }       
    }

    public function time(Request $request) {
        $validate = $request->validate([
            'time' => 'max:255|required'
        ]);
        if($validate) {
            Link::where('id', $request->id)->update([
                'time' => $request->time
            ]);
            return response()->json([
                'success' => 'Time successfully set'
            ], 200);
        }       
    }

    public function timeRemaining($id, $time) {
        $currentTime        = Carbon::now();
        $convertCurrentTime = Carbon::parse($currentTime);
        $linkTime           = Link::where('id', $id)->first();
        $convertLinkTime    = Carbon::parse($linkTime->time);
        $diffInSeconds      = $convertCurrentTime->diffInSeconds($convertLinkTime);
        $diffInMinutes      = $convertCurrentTime->diffInMinutes($convertLinkTime);
        $diffInHours        = $convertCurrentTime->diffInHours($convertLinkTime);
        $diffInDays        = $convertCurrentTime->diffInDays($convertLinkTime);
        if ($convertLinkTime->isPast()) {
            return 'Expired';
        }

        if($diffInSeconds <= 60) {
            return 'in ' . $diffInSeconds . ' seconds';
        } elseif ($diffInSeconds >= 60 && $diffInSeconds <= 3600) {
            return 'in ' . $diffInMinutes . ' minutes';
        } elseif ($diffInSeconds >= 3600 && $diffInSeconds <= 86400) {
            return 'in ' . $diffInHours . ' hours';
        } elseif ($diffInSeconds >= 86400) {
            return 'in ' . $diffInDays . ' days';
        }
    }

    public function removeTime(Request $request) {
        Link::where('id', $request->id)->update([
            'time' => null
        ]);
        return response()->json([
            'success' => 'Time successfully removed'
        ], 200);
    }

    public function removePassword(Request $request) {
        Link::where('id', $request->id)->update([
            'password' => null
        ]);
        return response()->json([
            'success' => 'Password successfully removed'
        ], 200);
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
