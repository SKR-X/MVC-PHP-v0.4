<?php

namespace App\Core;

class View
{

    // В РОДИТЕЛЬСКОМ КОНТРОЛЛЕРЕ ТО ЖЕ САМОЕ НАЗВАНИЕ МЕТОДА!!!

    public function viewPage($pageName, $config, $queryArr, $cookieLang, $arg = NULL)
    {
        switch ($config) {
            case '404':
                $config = array('view' => '404View',
                    'title' => '404 Page',
                    'css' => 'Err',
                    'menu' => 'none',
                    'header' => '404err');
                break;
            case 'main':
                $config = array('view' => 'MainView',
                    'title' => '',
                    'css' => 'Main',
                    'header' => 'main');
                break;
        }
        if ($cookieLang === false) {
            $lang = $this->setLanguage("gb");
            if (file_exists(ROOT . '/app/pages/' . $pageName . '.php')) {
                require_once(ROOT . '/app/pages/' . $pageName . '.php');
            }
            return $lang;
        } else {
            $lang = $this->setLanguage($cookieLang);
            if (file_exists(ROOT . '/app/pages/' . $pageName . '.php')) {
                require_once(ROOT . '/app/pages/' . $pageName . '.php');
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
}
