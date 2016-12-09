<?php

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;

use App\Classes\Validation;

class Poll extends Model
{
    protected $table = "poll";
    public $timestamps = false;

    public static $validation_rules = [
        'user_id' => 'exists:user,id',
        'category_id' => 'exists:category,id',
        'question' => 'required|min:4',
        'poll_type' => 'required|in:local,global',
        'option_type' => 'required|in:multi,single',
        'duration' => 'required',
        'latitude' => 'required',
        'longitude' => 'required',
        'diameter' => 'required'
    ];
    
    protected $fillable = [
        'user_id', 'category_id', 'question', 'poll_type', 'option_type', 
        'duration', 'latitude', 'longitude', 'diameter'
    ];
    
    
    public static function isValid($data) {
        return Validation::isValid($data, self::$validation_rules);
    }
}
