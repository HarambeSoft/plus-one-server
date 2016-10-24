<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Classes\QueryValidator;
use DB;

use App\User;
use App\Poll;

class UserController extends Controller
{
    public function index() {
        $all_users = User::all();
        return response()->json($all_users);
    }
    
    public function show($id) {
        $user = User::find($id);
        return response()->json($user);
    }

    public function polls($id) {
        $polls = Poll::where("user_id", $id)->get();
        return response()->json($polls);
    }
    
    public function notifications($id) {
        
    }
}
