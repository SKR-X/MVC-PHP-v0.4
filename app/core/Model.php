<?php

namespace App\Core;

use App\Core\Github\SafeMySQL as SafeMySQL;
use Exception;

class Model extends SafeMySQL
{
    public $queryResult;
    public $queryArr;
    
    public function __construct()
    {
        parent::__construct(array(
            'host' => 'localhost', 'user' => 'root', 'pass' => '', 'db' => 'default', 'charset' => 'utf8'
        ));
    }


    public function takeIdFromTable($id, $table)
    {
        try {
        return $this->getAll('SELECT ?n FROM ?n', $id, $table);
        } catch(Exception $e) {
            return array();
        }
    }

    public function takeIdFromTableOrder($id, $table, $by)
    {
        return $this->getAll('SELECT ?n FROM ?n ORDER BY ?n ASC', $id, $table, $by);
    }

    public function takeIdFromTableWhereEqually($id1, $table,$id2,$value)
    {
        return $this->getAll('SELECT ?n FROM ?n WHERE ?n = ?s', $id1, $table,$id2,$value);
    }

    public function takeOneFromTableWhereEqually($id1, $table, $id2, $name)
    {
        return $this->getOne('SELECT ?n FROM ?n WHERE ?n = ?s', $id1, $table, $id2, $name);
    }

    public function takeAllFromTable($table)
    {
        try {
        return $this->getAll('SELECT * FROM ?n', $table);
        } catch(Exception $e) {
            return array();
        }
    }

    public function takeAllFromTableOrder($table,$by)
    {
        try {
        return $this->getAll('SELECT * FROM ?n ORDER BY ?n ASC', $table, $by);
        } catch (Exception $e) {
            return array();
        }
    }

    public function takeAllFromTableWhereOrder($table, $id, $data, $by)
    {
        if (is_int($data)) {
            try {
                return $this->getAll('SELECT * FROM ?n WHERE ?n = ?i ORDER BY ?n ASC', $table, $id, $data, $by);
            } catch (Exception $e) {
                return array();
            }
        } else {
            try {
                return $this->getAll('SELECT * FROM ?n WHERE ?n = ?s ORDER BY ?n ASC', $table, $id, $data, $by);
            } catch (Exception $e) {
                return array();
            }
        }
    }

    public function takeAllFromTableLimit($table,$limiter)
    {
        try {
        return $this->getAll('SELECT * FROM ?n LIMIT ?i', $table,$limiter);
        } catch(Exception $e) {
            return array();
        }
    }

    public function takeAllFromTableWhereEqually($table, $id, $data)
    {
        if (is_int($data)) {
            return $this->getAll('SELECT * FROM ?n WHERE ?n = ?i', $table, $id, $data);
        } else {
            return $this->getAll('SELECT * FROM ?n WHERE ?n = ?s', $table, $id, $data);
        }
    }

    public function insertArray($table, $values)
    {
        return $this->query('INSERT INTO ?n SET ?u', $table, $values);
    }

    public function updateArray($table, $values, $params)
    {
        return $this->query('UPDATE ?n SET ?u WHERE ?n = ?s', $table, $values, $params['name'], $params['str']);
    }

    public function countTable($table) {
        try {
            return $this->numRows($this->query('SELECT * FROM ?n',$table));
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteAllFromTableWhereEqually($table, $id, $name)
    {
        return $this->query('DELETE FROM ?n WHERE ?n = ?s', $table, $id, $name);
    }

    public function deleteTable($table)
    {
        try {
            $this->query('DROP TABLE ?n', $table);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function takeAllFromTableWhereLimit($table, $id, $data, $limiter)
    {
        try {
            return $this->getAll('SELECT * FROM ?n WHERE ?n = ?s LIMIT ?i', $table, $id, $data, $limiter);
        } catch (Exception $e) {
            return array();
        }
    }

    public function takeAllFromTableWhereLimitOrder($table, $id1, $data, $limiter,$id2,$how)
    {
        try {
            return $this->getAll('SELECT * FROM ?n WHERE ?n = ?s ORDER BY ?n ?p LIMIT ?i', $table, $id1, $data, $id2, $how, $limiter);
        } catch (Exception $e) {
            return array();
        }
    }

    public function takeAllFromTableLimitOrder($table,$limiter,$id2,$how)
    {
        try {
            return $this->getAll('SELECT * FROM ?n ORDER BY ?n ?p LIMIT ?i', $table, $id2, $how, $limiter);
        } catch (Exception $e) {
            return array();
        }
    }
}
