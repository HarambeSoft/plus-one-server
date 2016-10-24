<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

use App\Poll;
use App\PollOptions;


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
    
    public function options($id) {
        $options = PollOptions::find($id);
        return response()->json($options);
    }
}
