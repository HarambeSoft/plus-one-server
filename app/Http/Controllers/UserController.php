<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Classes\QueryValidator;
use DB;

class UserController extends Controller
{
    public function index($id) {
        $users = DB::select('SELECT * FROM Member WHERE Member.id=?', [$id]);
        list($success, $result) = QueryValidator::notEqToOne($users);
        return response()->json($result);
    }

    public function polls($id) {
        $polls = DB::select('SELECT * FROM Poll WHERE Poll.memberID=?', [$id]);
        return response()->json($polls);
    }
}
