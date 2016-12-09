<?php

namespace App\Classes;

use App\Classes\Str;

class Path {
    public static function join() {
        $path = "";
        $path_count = func_num_args();

        if($path_count < 2)
            return -1;

        for ($i = 0; $i < $path_count; $i++) {
            $curr_path = func_get_arg($i);
            $path = $path . $curr_path;

            if(!Str::ends_with($curr_path, '/'))
                $path = $path . '/';
        }

        $path = rtrim($path, '/');
        return $path;
    }
}
