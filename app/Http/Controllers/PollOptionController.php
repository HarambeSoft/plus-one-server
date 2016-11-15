<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PollOption;
use App\PollOptionComment;
use App\Comment;

class PollOptionController extends Controller
{
    public function option($id) {
        $option = PollOption::find($id);
        return response()->json($option);
    }
    
    public function comments($id) {
        $option_comments_id = PollOptionComment::where('poll_option_id', $id)->pluck('id');
        $comments = Comment::whereIn('id', $option_comments_id)->get();
        return response()->json($comments);
    }
}
