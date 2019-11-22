<?php

namespace App\Core;

class Session
{

    public function sessionStart($name = "")
    {
        if (isset($name)) {
            $_SESSION[$name] = true;
            return true;
        } else {
            return false;
        }
    }

    public function sessionStop($name = "")
    {
        if (isset($name)) {
            $_SESSION[$name] = false;
            return true;
        } else {
            return false;
        }
    }

    public function sessionCheck($name = "")
    {
        if(isset($_SESSION[$name])) {
            return true;
        } else {
            return false;
        }
    }
}
