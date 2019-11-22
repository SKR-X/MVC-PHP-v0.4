<?php

namespace App\Core;

use App\Core\View as View;

class Controller
{
    
    private $View;
    
    protected function viewPage($pageName, $config, $queryArr = array())
    {
        $this->View = new View();
        $this->View->viewPage($pageName, $config, $queryArr);
    }

}
