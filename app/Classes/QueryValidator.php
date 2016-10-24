<?php

namespace App\Classes;

class QueryValidator {
    /*
        returns {"error": "$details"} as json.
    */
    public static function errorJson($details) {
        return \response()->json(array('error' => $details));
    }

    /*
        returns array($success, $result)
        $success = False if the result array's count is not equal to zero.
        $result = first element of the $arr
    */
    public static function notEqToOne(&$arr) {
        $resultCount = count($arr);
        if ($resultCount != 1)
            return array(False, self::errorJson("Not found. " . $resultCount . " results."));
        return array(True, $arr[0]);
    }
}

?>
