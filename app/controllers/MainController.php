<?php

namespace App\Controllers;

use App\Core\Controller as Controller;

use App\Core\Model as Model;

// Основной контроллер вывод 404 / глав. страницы

class MainController extends Controller
{
    private $Model;

    // private $pageInDB;

    public function __construct()
    {
        $this->Model = new Model();
    }

    public function actionErr()
    {
        $this->viewPage('404Page', '404');
    }

    public function actionMain()
    {
        $this->viewPage('MainPage', 'main');
    }
}
