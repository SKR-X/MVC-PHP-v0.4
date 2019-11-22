<?php

namespace App\Core;

class View
{

    private $configArr = array('404err' => array("view" => "404View",
        "title" => "404 Page",
        "css" => "Err",
        "header" => "404 Page",
        "menu" => "none"), 'Main' => array("view" => "MainView",
        "title" => "Main Page",
        "css" => "main",
        "header" => "Main Page",
        "menu" => "none"));
    
    public function viewPage($pageName, $config, $queryArr)
    {
        if(isset($this->configArr[$config])) {
            $config = $this->configArr[$config];
        }
        if (file_exists(ROOT . '/app/pages/' . $pageName)) {
            require_once(ROOT . '/app/pages/' . $pageName);
        }
    }
    
}
