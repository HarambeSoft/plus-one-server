<?php

namespace App\Http\Controllers;

use App\Classes\Firebase;
use Illuminate\Http\Request;
use App\Http\Requests;

use DB;
use Auth;

use App\User;
use App\Poll;
use App\PollOption;
use App\Comment;    
use App\PollComment;


class PollController extends Controller
{
    function __construct() {
        $this->middleware('auth:api');
    } 
    
    public function index() {
        $all_polls = Poll::all();
        return response()->json($all_polls);
    }
    
    public function show($id) {
        $poll = Poll::find($id);
        $poll->options = PollOption::where('poll_id', $id)->get();
        return response()->json($poll);
    }
    
    public function store(Request $request) {
        extract(Poll::isValid($request->all())); // $is_valid, $validation_message
        
        if (!$is_valid)
            return self::result(true, $validation_message);

        $user_id = Auth::guard('api')->user()->id;

        $poll = new Poll($request->all());
        $poll->user_id = $user_id;
        if ($poll->save()) {
            // Send notification to near users
            $data = ['poll_id' => $poll->id];
            $near_users = Firebase::findNearUsers($request->latitude, $request->longitude, $request->diameter);

            foreach ($near_users as $near_user) {
                $id = $near_user;
                if ($near_user != $user_id)
                    Firebase::sendNotificationToUser($id, "New poll near you", "Maybe you want to answer?", $data);
            }

            return response()->json(['error' => false, 
                                       'message' => 'Poll created successfully.',
                                       'response' => $poll]);
       } else {
            return self::result(true, 'Can not create poll.');
       }
    }
    
    public function options($id) {
        $options = PollOption::where('poll_id', $id)->get();
        return response()->json($options);
    }
    
    public function comments($id) {
        $poll_comments_id = PollComment::where('poll_id', $id)->pluck('comment_id');
        $comments = Comment::whereIn('id', $poll_comments_id)->get();
        return response()->json($comments);
    }

    public function near(Request $request) {
        $R = 6371;

        $lat = $request->latitude;
        $long = $request->longitude;
        $rad = $request->diameter;

        $maxLat = $lat + rad2deg($rad/$R);
        $minLat = $lat - rad2deg($rad/$R);
        $maxLong = $long + rad2deg(asin($rad/$R) / cos(deg2rad($lat)));
        $minLong = $long - rad2deg(asin($rad/$R) / cos(deg2rad($lat)));

        // id, name, password, email, xp, create_date, full_name, gender, country, city, profession
        $sql = "Select user_id, category_id, id, question, poll_type, option_type, stat, duration, latitude, longitude, diameter
                From (
                Select *, acos(sin(:lat)*sin(radians(latitude)) + cos(:lat)*cos(radians(latitude))*cos(radians(longitude)-:lon)) * :R As D
                From (
                    Select *
                    From poll
                    Where latitude Between :minLat And :maxLat
                      And longitude Between :minLon And :maxLon
                ) As FirstCut
            Where acos(sin(:lat)*sin(radians(latitude)) + cos(:lat)*cos(radians(latitude))*cos(radians(longitude)-:lon)) * :R < :rad
            Order by D
            ) As TotalCut";
        $params = [
            ':lat'    => deg2rad($lat),
            ':lon'    => deg2rad($long),
            ':minLat' => $minLat,
            ':minLon' => $minLong,
            ':maxLat' => $maxLat,
            ':maxLon' => $maxLong,
            ':rad'    => $rad,
            ':R'      => $R,
        ];
        $sql = str_replace(array_keys($params), $params, $sql);
        // FIXME: wtf is this shit
        // dont use replace, use query builder or something safe

        return DB::select($sql);
    }
}
