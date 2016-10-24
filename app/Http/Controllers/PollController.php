<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\Classes\QueryValidator;
use DB;


class PollController extends Controller
{
    public function index($id) {
        $polls = DB::select('SELECT * FROM Poll WHERE Poll.id=?', [$id]);
        list($success, $result) = QueryValidator::notEqToOne($polls);
        
        if ($success) {
            $options = DB::select('SELECT * FROM PollOption WHERE
                                                  PollOption.pollID=?', [$id]);
            $result->options = $options;
        }

        return response()->json($result);
    }

    public function comments($id) {

    }
}
