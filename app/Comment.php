<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Classes\Validation;

class Comment extends Model
{
    protected $table = "comment";
    public $timestamps = false;
    
    public static $validation_rules = [
        'user_id' => 'exists:user,id',
        'content' => 'required|min:2',
    ];
    
    protected $fillable = [
        'user_id', 'content'
    ];
    
    
    public static function isValid($data) {
        return Validation::isValid($data, self::$validation_rules);
    }
}
