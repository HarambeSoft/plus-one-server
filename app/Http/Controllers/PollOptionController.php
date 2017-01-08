<?php

namespace App\Http\Controllers;

use App\Poll;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\PollOption;
use App\PollOptionComment;
use App\Comment;
use App\UserPollOption;
use App\UserPoll;

use Auth;

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

    public function vote($poll_id, $option_id) {
        $user = Auth::guard('api')->user();
        $user_poll = UserPoll::where([['user_id', '=', $user->id],
                                      ['poll_id', '=', $poll_id]])->first();
        $user_poll_id = 0;
        if ($user_poll == null) {
            $new_user_poll = new UserPoll;
            $new_user_poll->user_id = $user->id;
            $new_user_poll->poll_id = $poll_id;
            $new_user_poll->save();

            $user_poll_id = $new_user_poll->id;
        } else {
            $user_poll_id = $user_poll->id;
        }

        $user_poll_option = UserPollOption::where([['user_poll_id', '=', $user_poll_id],
                                                   ['poll_option_id', '=', $option_id]])->first();


        if ($user_poll_option == null) {
            $poll = Poll::find($poll_id);
            if ($poll->option_type == 'single') {
                // Delete other voted options and decrase their votes, if any
                $user_poll_options = UserPollOption::where('user_poll_id', $user_poll_id);

                foreach ($user_poll_options->get() as $upo) {
                    $current_poll_id = $upo->poll_option_id;
                    $poll_option = PollOption::find($current_poll_id);
                    $poll_option->vote = $poll_option->vote - 1;
                    $poll_option->save();
                }

                $user_poll_options->delete();
            }

            $new_user_poll_option = new UserPollOption;
            $new_user_poll_option->user_poll_id = $user_poll_id;
            $new_user_poll_option->poll_option_id = $option_id;
            $new_user_poll_option->save();

            $poll_option = PollOption::find($option_id);
            $poll_option->vote = $poll_option->vote + 1;
            $poll_option->save();

            return self::result(false, "Vote success");
        } else {
            return self::result(false, "Already voted");
        }
    }

    public function unvote($poll_id, $option_id) {
        $user = Auth::guard('api')->user();

        $user_poll =UserPoll::where([['user_id', '=', $user->id],
                                     ['poll_id', '=', $poll_id]])->first();

        if ($user_poll != null) {
            $user_poll_option = UserPollOption::where([['user_poll_id', '=', $user_poll->id],
                                                       ['poll_option_id', '=', $option_id]])->first();

            if ($user_poll_option != null) {
                $user_poll_option->delete();

                $poll_option = PollOption::find($option_id);
                $poll_option->vote = $poll_option->vote - 1;
                $poll_option->save();

                return self::result(false, "Unvote success");
            } else {
                return self::result(true, "User did not vote this option.");
            }
        } else {
            return self::result(false, 'User did not have vote on this poll.');
        }
    }
}
