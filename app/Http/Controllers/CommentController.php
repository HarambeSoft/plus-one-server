<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Classes\Firebase;

use App\Comment;
use App\PollComment;
use App\Poll;

use Auth;

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
            return self::result(true, $validation_message);

        $comment = new Comment($request->all());
        $comment->save();
        
        return self::result(false, 'Comment succesfully added.');
    }
    
    public function commentPoll($id /*is poll_id*/, Request $request) { 
        $comment_data = ['user_id' => Auth::guard('api')->user()->id,
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
                $poll_comment->save();
                
                $poll = Poll::find($id);
                $notif_title = 'PlusOne';
                $notif_body = Auth::guard('api')->user()->name . ' has commented to your poll.';
                Firebase::sendNotificationToUser($poll->user_id, $notif_title, $notif_body);
                
                return self::result(false, 'Commented to poll succesfully.');
            }
        }
        return self::result(true, 'Can not comment to poll.');
    }
    
    
    public function upvote($id) {
        $comment = Comment::find($id);
        $comment->up_vote = $comment->up_vote + 1;
        
        if ($comment->save()) {
            $notif_title = 'PlusOne';
            $notif_body = Auth::guard('api')->user()->name . ' upvoted your comment.';
            Firebase::sendNotificationToUser($comment->user_id, $notif_title, $notif_body);

            return self::result(false, 'Upvote succes.');
        } else {
            return self::result(true, 'Cannot upvote.');
        }
    }
    
    public function downvote($id) {
        $comment = Comment::find($id);
        $comment->down_vote = $comment->down_vote + 1;

        if ($comment->save()) {
            $notif_title = 'PlusOne';
            $notif_body = Auth::guard('api')->user()->name . ' downvoted your comment.';
            Firebase::sendNotificationToUser($comment->user_id, $notif_title, $notif_body);

            return self::result(false, 'Downvote succes.');
        } else {
            return self::result(true, 'Cannot downvote.');
        }
    }
}
