<?php

namespace App\Classes;


class Str {
    public static function starts_with($haystack, $needle) {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }

    public static function ends_with($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0)
            return true;
        return (substr($haystack, -$length) === $needle);
    }
}

?>
