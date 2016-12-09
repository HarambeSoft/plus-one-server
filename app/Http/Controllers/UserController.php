<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use DB;
use Hash;

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
    
    public function store(Request $request) {
        extract(User::isValid($request->all()));
        
        if (!$is_valid)
            return response()->json(['error' => true,
                                      'message' => $validation_message]);
    
        $user = new User;
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->email = $request->input('email');
        
        $user->save();
        
        return response()->json(['error' => false,
                                   'message' => 'User succesfully created.']);
    }

    public function polls($id) {
        $polls = Poll::where("user_id", $id)->get();
        return response()->json($polls);
    }
    
    public function notifications($id) {
        
    }
}
