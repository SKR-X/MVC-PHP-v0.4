<?php

//Вывод ошибок 0/1

ini_set('display_errors',1);
error_reporting(E_ALL);

//Константы

define('ROOT',$_SERVER['DOCUMENT_ROOT']);

define('TEMPLATE',ROOT.'/app/content/templates/');

define('VIEW',ROOT.'/app/content/views/');