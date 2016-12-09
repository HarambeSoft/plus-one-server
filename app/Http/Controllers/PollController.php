<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use DB;

use App\Poll;
use App\PollOption;
use App\Comment;    
use App\PollComment;


class PollController extends Controller
{
    public function index() {
        $all_polls = Poll::all();
        return response()->json($all_polls);
    }
    
    public function show($id) {
        $poll = Poll::find($id);
        return response()->json($poll);
    }
    
    public function store(Request $request) {
        extract(Poll::isValid($request->all()));
        
        if (!$is_valid)
            return response()->json(['error' => true,
                                      'message' => $validation_message]);

        $poll = new Poll($request->all());
        $poll->save();
        
        return response()->json(['error' => false,
                           'message' => 'Poll succesfully created.']);
    }
    
    public function options($id) {
        $options = PollOption::where('poll_id', $id)->get();
        return response()->json($options);
    }
    
    public function comments($id) {
        $poll_comments_id = PollComment::where('poll_id', $id)->pluck('id');
        $comments = Comment::whereIn('id', $poll_comments_id)->get();
        return response()->json($comments);
    }
}
