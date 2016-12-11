<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Comment;
use App\PollComment;


class CommentController extends Controller
{
    public function index() {
        $all_comments = Comment::all();
        return response()->json($all_comments);
    }
    
    public function show($id) {
        $comment = Comment::find($id);
        return response()->json($comment);
    }
    
    public function store(Request $request) {
        extract(Comment::isValid($request->all())); // $is_valid, $validation_message
        
        if (!$is_valid)
            return response()->json(['error' => true,
                                      'message' => $validation_message]);

        $comment = new Comment($request->all());
        $comment->save();
        
        return response()->json(['error' => false,
                           'message' => 'Comment succesfully added.']);
    }
    
    public function commentPoll($id /*is poll_id*/, Request $request) { 
        $comment_data = ['user_id' => $request->user_id,
                          'content' => $request->content];
        if (Comment::isValid($comment_data)) {
            // Add comment
            $comment = new Comment($comment_data);
            $has_commented = $comment->save();

            // Bind comment to poll
            $poll_comment_data = ['comment_id' => $comment->id, 
                                  'poll_id' => $id];
            if ($has_commented && PollComment::isValid($poll_comment_data)) {
                $poll_comment = new PollComment($poll_comment_data);
                echo $poll_comment->save();
                
                return response()->json(['error' => false,
                                           'message' => 'Commented to poll succesfully.']);
            }
        }
    }
    
    
    public function upvote($id) {
        $comment = Comment::find($id);
        $comment->up_vote = $comment->up_vote + 1;
        if (!$comment->save())
            return response()->json(['error' => false, 
                                      'message' => 'Upvote succes.']);
        else
            return response()->json(['error' => true, 
                                       'message' => 'Cannot upvote.']);
    }
    
    public function downvote($id) {
        $comment = Comment::find($id);
        $comment->down_vote = $comment->down_vote + 1;
        $comment->save();
    }
}
