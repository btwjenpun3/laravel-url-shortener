<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function activityCreate($id, $user, $link, $shortlink, $title, $password){
        $title      = $title ?: 'null';
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> created shortlink with Original URL <b>' . $link . ' </b> and Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b> with Title <b>' . $title . '</b> and Password <b>' . $password . '</b>';
    }

    public function activityDelete($id, $user, $link, $shortlink){
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> delete shortlink with Original URL <b>' . $link . ' </b> and Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b>';
    }

    public function activityEdit($id, $user, $link, $oldShortlink, $newShortlink, $oldTitle, $newTitle){
        $oldTitle = $oldTitle ?: 'null';
        $newTitle = $newTitle ?: 'null';
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> edit shortlink with Original URL <b>' . $link . ' </b> and Short URL from <b>' . env('APP_URL') . '/' . $oldShortlink . '</b> to <b> ' . env('APP_URL') . '/' . $newShortlink . '</b> and Title from <b>' . $oldTitle . '</b> to <b>' . $newTitle . '</b>';
    }

    public function activityPasswordSet($id, $user, $shortlink) {
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> set Password for Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b>';
    }

    public function activityPasswordRemove($id, $user, $shortlink) {
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> remove Password for Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b>';
    }

    public function activityTimeSet($id, $user, $shortlink, $time) {
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> set Time for Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b> to <b>' . $time . '</b>';
    }

    public function activityTimeRemove($id, $user, $shortlink) {
        return 'User <b>' . $user . '<i> (' . $id . ')</i></b> remove Time for Short URL <b>' . env('APP_URL') . '/' . $shortlink . '</b>';
    }

    public function index() {
        return view('activity.index');
    }

    public function activityDataTable() {
        $activities = Activity::select(['activities.id', 'users.email', 'activities.action', 'activities.log', 'activities.created_at'])
                                    ->leftJoin('users', 'users.id', '=', 'activities.user_id');
        return DataTables::of($activities)->toJson();
    }
}
