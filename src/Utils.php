<?php

namespace App;

use App\Models\User;
use Exception;

class Utils {

    private static $user;
    /**
     * @throws Exception
     */
    public static function set_user_from_basic_auth($user)
    {
       self::$user = $user;
    }

    public static function get_user_from_basic_auth(){
        return self::$user;
    }
}