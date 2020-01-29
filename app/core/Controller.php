<?php

namespace App\Core;

use App\Core\View as View;

class Controller
{
    private $View;

protected function viewPage($pageName,$config,$queryArr = array())
{
    $this->View = new View();
    $this->View->ViewPage($pageName, $config, $queryArr,$this->checkCookie('lang'));
}

protected  function viewError($arg)
{
    $this->View = new View();
    $this->View->ViewPage('ErrPage.php', array(), array(), $this->checkCookie('lang'), $arg);
}

private function checkCookie($name)
{
    if(isset($_COOKIE[$name])) 
    {
        return $_COOKIE[$name];
    } else {
        return false;
    }
}

// protected static function ViewPageDB($config  = array()){
//     View::ViewPageDB($config);
// }
}
