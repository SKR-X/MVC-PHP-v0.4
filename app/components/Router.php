<?php

namespace App\Components;

use App\Controllers\MainController as MainController;

class Router
{

    static private $controller;
    static private $action;
    static private $route;

    //возвращает строку роута из URL

    static private function getUri()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        }
    }

    //Вывод 404

    static public function throwErr()
    {
        $main = new MainController();
        $main->actionErr();
    }

    //Вывод главной страницы

    static public function throwMain()
    {
        $main = new MainController();
        $main->actionMain();
    }

    //Проверка экшена

    static public function checkAction()
    {
        $controller = 'App\\Controllers\\' . self::$controller;
        $class = new $controller;
        $method = self::$action;
        if (!method_exists($class, $method)) {
            self::throwErr();
            exit();
        }

        $all = array(
            'controller' => $class,
            'action' => $method
        );
        return $all;
    }

    //Вызываем экшен, перед этим проверяя наличие нужного контроллера и экшена

    static public function callAction()
    {
        if (class_exists('App\\Controllers\\' . self::$controller)) {
            $array = self::checkAction();
            $cont = $array['controller'];
            $act = $array['action'];
            $cont->$act();
        } else {
            self::throwErr();
        }
    }

    static public function run()
    {
        self::$route = self::getUri();

        //Разбираем путь на контроллер и экшен

        self::$route = explode('/', self::$route);
        if (isset(self::$route[0]) && isset(self::$route[1])) {
                self::$controller = ucfirst(self::$route[0]) . "Controller";
                self::$action = "action" . ucfirst(self::$route[1]);
                self::callAction();
        } elseif (!empty(self::$route[0])) {
                self::$controller = ucfirst(self::$route[0]) . "Controller";
                self::$action = "actionIndex";
                self::callAction();
        } else {
            self::throwMain();
        }
    }
}
