<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\PollOption;
use App\PollOptionComment;
use App\Comment;

class PollOptionController extends Controller
{

    public function store($poll_id, Request $request) {
        
        foreach ($request->all() as $item) {
            $poll_option = new PollOption;
            $poll_option->poll_id = $poll_id;
            $poll_option->content = $item['content'];
            
            if (!$poll_option->save())
                return self::result(true, 'Error while adding option.');
        }
        return self::result(false, 'Option added successfully.');
    }

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
