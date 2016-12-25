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

        $poll = new Poll($request->all());
        $poll->user_id = Auth::guard('api')->user()->id;
        if ($poll->save()) {
            // Send notification to near users
            $near_users = Firebase::findNearUsers($request->latitude, $request->longitude, $request->diameter);
            foreach ($near_users as $near_user) {
                //FIXME: send notification directly to user id (also needs work on android side)
                $id = $near_user;
                $user_name = User::find($id)->name;
                Firebase::sendNotificationToUser($user_name, "New poll near you", "Maybe you want to answer?");
                //TODO: add data to notification that enables to open poll when click on notification
                //FIXME: dont send notification to poll-creator
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
}
