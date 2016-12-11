<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Classes\Validation;

class PollComment extends Model
{
    protected $table = "poll_comment";
    public $timestamps = false;

    public static $validation_rules = [
        'comment_id' => 'required|exists:comment,id',
        'poll_id' => 'required|exists:poll,id',
    ];
    
    protected $fillable = [
        'comment_id', 'poll_id'
    ];
    
    
    public static function isValid($data) {
        return Validation::isValid($data, self::$validation_rules);
    }
}
