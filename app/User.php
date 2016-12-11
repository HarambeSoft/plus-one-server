<?php

namespace App;

use Validator;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Classes\Validation;

class User extends Authenticatable
{
    use Notifiable;
    
    public $timestamps = false;
    protected $table = "user";

    protected $hidden = [
        'password', 'api_token',
    ];
    
    public static $validation_rules = [
        'name' => 'required|min:2|unique:user',
        'email' => 'required|email|unique:user',
        'password' => 'required|min:8'
    ];
    
    public static function isValid($data) {
        return Validation::isValid($data, self::$validation_rules);
    }
}
