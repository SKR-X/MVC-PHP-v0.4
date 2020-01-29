<?php

namespace App\Models;

use App\Core\Model as Model;
use Exception;

class ChampModel extends Model
{

    private $urlName;

    public function __construct($urlName)
    {
        $this->urlName = $urlName;
        parent::__construct();
    }

    public function categoriesInner()
    {
        if (!empty($this->takeAllFromTable($this->urlName . '_categories'))) {
            $array = $this->getAll('SELECT ?p, ?p, ?p, ?p FROM ?p INNER JOIN ?p ON ?p = ?p ORDER BY ?p ASC', $this->urlName  . '_categories.CategoryName', $this->urlName  . '_categories.CategoryFileDraw', $this->urlName  . '_categories.Categorytatami', $this->urlName  . '_categories.CategoryId', $this->urlName  . '_categories', $this->urlName  . '_participants', $this->urlName  . '_categories.CategoryId', $this->urlName  . '_participants.CategoryId', $this->urlName  . '_categories.CategoryId');
            return $array;
        } else {
            return array();
        }
    }

    public function countriesInner()
    {
        if (!empty($this->takeAllFromTable($this->urlName  . '_participants'))) {
            $array = $this->getAll('SELECT ?p, ?p, ?p, ?p, ?p FROM ?p INNER JOIN ?p ON ?p = ?p ORDER BY ?p ASC', 'countries.CountryNameRu', 'countries.CountryNameUa', 'countries.CountryNameEn', 'countries.CountryId', 'countries.CountryFlag', 'countries', $this->urlName  . '_participants', 'countries.CountryId', $this->urlName  .'_participants.CountryId', 'countries.CountryId');
            return $array;
        } else {
            return array();
        }
    }

    public function clubsInner()
    {
        if (!empty($this->takeAllFromTable($this->urlName  . '_participants'))) {
            $array = $this->getAll('SELECT ?p, ?p FROM ?p INNER JOIN ?p ON ?p = ?p ORDER BY ?p ASC', 'clubs.ClubId', 'clubs.ClubName', 'clubs', $this->urlName  . '_participants', 'clubs.ClubId', $this->urlName  .'_participants.ClubId', 'clubs.ClubId');
            return $array;
        } else {
            return array();
        }
    }

    public function onlineTatami()
    {
        return $this->getAll('SELECT * FROM ?n WHERE TatamiId = ?i ORDER BY Id LIMIT ?i,1000',$this->urlName .'_tatami_online',$_GET['online'],(($this->getOne('SELECT Fight FROM ?n WHERE TatamiId = ?i',$this->urlName .'_tatami',$_GET['online'])) ? $this->getOne('SELECT Fight FROM ?n WHERE TatamiId = ?i',$this->urlName .'_tatami',$_GET['online'])-1 : 0));
    }

    public function categoriestatami()
    {
        if (!empty($this->takeAllFromTable($this->urlName  . '_categories'))) {
            $arr1 = $this->takeIdFromTableOrder('Categorytatami', $this->urlName  . '_categories','Categorytatami');
            $counter = 0;
            for ($i = 0; $i < count($arr1); $i++) {
                if ($i == 0 || $arr1[$i]['Categorytatami'] != $arr1[$i - 1]['Categorytatami']) {
                    $arr2[$counter] = $arr1[$i]['Categorytatami'];
                    $counter++;
                }
            }
            for ($b = 0; $b < count($arr2); $b++) {
                $returnArray[$b] = array('id' => $arr2[$b], 'categories' => $this->getAll('SELECT CategoryName FROM ?n WHERE Categorytatami = ?i', $this->urlName  . '_categories', $arr2[$b]));
            }
            return $returnArray;
        }
    }
}