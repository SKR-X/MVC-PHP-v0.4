<?php

namespace App\Core;

class View
{

    // В РОДИТЕЛЬСКОМ КОНТРОЛЛЕРЕ ТО ЖЕ САМОЕ НАЗВАНИЕ МЕТОДА!!!

    public function viewPage($pageName, $config, $queryArr, $cookieLang, $arg = NULL)
    {
        switch ($config) {
            case 'loginInfoADM':
                $config = array(
                    'view' => 'PostInfoLoginView',
                    'title' => 'Error',
                    'css' => 'Admin',
                    'menu' => 'none',
                    'header' => 'panel'
                );
                break;
            case 'panelADM':
                $config = array(
                    'view' => 'AdminPanelView',
                    'title' => 'Panel',
                    'css' => 'Admin',
                    'menu' => 'none',
                    'header' => 'panel'
                );
                break;
            case 'loginADM':
                $config = array(
                    'view' => 'AdminLoginView',
                    'title' => 'Login',
                    'css' => 'Admin',
                    'menu' => 'none',
                    'header' => 'login'
                );
                break;
            case 'champ':
                $config = array(
                    'view' => 'ChampView',
                    'title' => 'Alliance Kumite',
                    'css' => 'Champ'
                );
                break;
            case 'reg':
                $config = array(
                    'view' => 'RegView',
                    'title' => 'Register',
                    'css' => 'Reg',
                    'header' => 'reg'
                );
                break;
            case '404':
                $config = array('view' => '404View',
                    'title' => '404 Page',
                    'css' => 'Err',
                    'menu' => 'none',
                    'header' => '404err');
                break;
            case 'main':
                $config = array('view' => 'MainView',
                    'title' => 'Alliance Kumite',
                    'css' => 'Main',
                    'menu' => 'none',
                    'header' => 'main');
                break;

        }
        if ($cookieLang === false) {
            $lang = $this->setLanguage("gb");
            if (file_exists(ROOT . '/app/pages/' . $pageName)) {
                require_once(ROOT . '/app/pages/' . $pageName);
            }
            return $lang;
        } else {
            $lang = $this->setLanguage($cookieLang);
            if (file_exists(ROOT . '/app/pages/' . $pageName)) {
                require_once(ROOT . '/app/pages/' . $pageName);
            }
            return $lang;
        }
    }

    private function setLanguage($langName)
    {
        if (file_exists(ROOT . '/app/content/langs/' . $langName . '.ini')) {
            return parse_ini_file(ROOT . '/app/content/langs/' . $langName . '.ini', TRUE);
        }
    }


    // старый бред

    // -- Массив $array обязан образовываться от работы метода configTake() (Core/Model.php) --

    // Метод для вывода страницы с указанным конфигом в БД

    // public static function viewPageDB($array){
    //     $config = $array['config'];
    //     $query = $array['queryArr'];
    //     if(file_exists(ROOT.'/app/pages/'.$config['pagename'].'Page.php')){
    //     require_once (ROOT.'/app/pages/'.$config['pagename'].'Page.php');   
    //     }
    // }

}
