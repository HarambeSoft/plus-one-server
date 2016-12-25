<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use DB;
use Hash;
use Auth;
use Illuminate\Support\Str;

use App\User;
use App\Poll;

class UserController extends Controller
{
    function __construct() {
        $this->middleware('auth:api', ['except' => ['token', 'store']]);
    } 
    
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
            return self::result(true, $validation_message);
    
        $user = new User;
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('password'));
        $user->email = $request->input('email');
        $user->api_token = Str::quickRandom(60); //FIXME: make it unique
        
        $user->save();
        
        return self::result(false, 'User succesfully created.');
    }
    
    public function update(Request $request, $id) {
        $user = User::find($id);

        $user->email = $request->email;
        $user->fullname = $request->fullname;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->profession = $request->profession;
        $user->gender = $request->gender;
        
        if($user->save())
            return self::result(false, 'Update successfull.');
        else
            return self::result(true, 'Something went wrong');
    }

    public function token(Request $request) {
        if(Auth::validate($request->all())) {
            $user = User::where('name', $request->name)->first();
            return response()->json(['error' => false, 
                                      'api_token' => $user->api_token,
                                      'user' => $user
            ]);
        }
        return self::result(true, 'Wrong credentials.');
    }

    public function polls($id) {
        $polls = Poll::where("user_id", $id)->get();
        return response()->json($polls);
    }
    
    public function notifications($id) {
        
    }
}
