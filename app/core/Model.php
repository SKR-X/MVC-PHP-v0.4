<?php

namespace App\Core;

use App\Core\Github\SafeMySQL as SafeMySQL;

class Model extends SafeMySQL
{

    public $queryArr;

    public function __construct()
    {
        parent::__construct(require_once(ROOT . '/app/config/dbconfig.php'));
    }


    public function takeAllFromTable($table)
    {
        $this->queryArr = $this->getAll('SELECT * FROM ?n', $table);
        return $this->queryArr;
    }

    public function takeAllFromTableWhereEqually($table, $id, $name)
    {
        $this->queryArr = $this->getAll('SELECT * FROM ?n WHERE ?n = ?s', $table, $id, $name);
        return $this->queryArr;
    }
}