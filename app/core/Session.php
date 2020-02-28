<?php

namespace App\Core;

class Session
{

    public static function sessionStart($name, $value = true)
    {
        $_SESSION[$name] = $value;
        return true;
    }

    public static function sessionStop($name)
    {
        if(isset($_SESSION[$name])) {
            $_SESSION[$name] = false;
            return true;
        } else {
            return false;
        }
    }

    public static function sessionCheck($name)
    {
        if (isset($_SESSION[$name])) {
            return true;
        } else {
            return false;
        }
    }

    public static function returnSession($name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return false;
        }
    }
}
