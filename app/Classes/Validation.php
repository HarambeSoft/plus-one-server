<?php

namespace App\Classes;

use Validator;

class Validation {
    public static function isValid($data, $validation_rules) {
        $validator = Validator::make($data, $validation_rules);
        $message = $validator->messages();
        
        return ['is_valid' => $validator->passes(), 
                 'validation_message' => $message];
    }
}
