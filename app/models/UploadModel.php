<?php

namespace App\Models;

use App\Core\Model as Model;

class UploadModel extends Model
{

    private $PNGuploadDir = ROOT . '/app/uploadedFiles/IMGfiles/';
    private $TXTuploadDir = ROOT . '/app/uploadedFiles/TXTfiles/';
    private $table;
    private $partFioArray;
    private $partBrArray;
    private $coachUserNameArray;
    private $coachClubArray;
    private $insertArray = array();
    private $zip;

    public function __construct()
    {
        $this->zip = new \ZipArchive();
        parent::__construct();
    }

    private function decode($data)
    {
        return iconv('windows-1251', 'utf-8', $data);
    }

    private function toTimeYMD($data)
    {
        return date("Y-m-d",strtotime($data));
    }

    public function uploadPostDataParams($urlName, $champName, $startReg, $endReg, $beginChamp, $endChamp, $typeChamp, $tatami)
    {
        $this->updateArray('champpages', array(
            'ChampName' => $champName,
            'DateStartReg' => $this->toTimeYMD($startReg),
            'DateEndReg' => $this->toTimeYMD($endReg),
            'DateBeginChamp' => $this->toTimeYMD($beginChamp),
            'DateEndChamp' => $this->toTimeYMD($endChamp),
            'TypeChamp' => $typeChamp,
            'tatamiCount' => $tatami
        ), array(
            'name' => 'UrlName',
            'str' => $urlName
        ));
    }

    public function updatePNGData($urlName, $files) 
    {
        $filename = strtolower(basename($files['file']['name']));
        if(file_exists($this->PNGuploadDir . $urlName .  '_' . $filename)) {
            unlink($this->PNGuploadDir . $urlName .  '_' . $filename);
        }
            if(!move_uploaded_file($files['file']['tmp_name'], $this->PNGuploadDir . $urlName .  '_' . $filename)) {
                $handle = fopen($this->PNGuploadDir . 'ERROR_LOG.TXT', 'w');
                fwrite($handle, 'Can\'t move_uploaded_file! File name is: ' . $this->decode($filename));
                fclose($handle);
                exit();
            }
            return true;
    }

    public function updateOnlineTatami($urlName,$curr,$count)
    {
        $this->updateArray($urlName.'_tatami', array(
            'Fight' => intval($curr),
        ), array(
            'name' => 'TatamiId',
            'str' => intval($count)
        ));
        return true;
    }

    public function uploadPostDatatatamiOnlineFile($urlName, $files)
    {
        $this->table = $urlName . '_tatami_online';
        if ($this->getAll('SELECT * FROM ?n', $this->table)) {
            $this->query('TRUNCATE ?n', $this->table);
        }
        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                    if(!$this->getAll('SELECT * FROM ?n WHERE TatamiId = ?i', $urlName.'_tatami',intval($data[0])) && !empty($data[0])) {
                    $this->insertArray($urlName.'_tatami',array('TatamiId' => intval($data[0]), 'Fight' => 1));
                    }
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['TatamiId'] = $data[$c];
                                break;
                            case 1:
                                $this->insertArray['NumFight'] = $data[$c];
                                break;
                            case 2:
                                $this->insertArray['OrdNumR'] = $this->decode($data[$c]);
                                break;
                            case 3:
                                $this->insertArray['FioR'] = $this->decode($data[$c]);
                                break;
                            case 4:
                                $this->insertArray['CountryR'] = $this->decode($data[$c]);
                                break;
                            case 5:
                                $this->insertArray['ClubR'] = $this->decode($data[$c]);
                                break;
                            case 6:
                                $this->insertArray['CoachR'] = $this->decode($data[$c]);
                                break;
                            case 7:
                                $this->insertArray['OrdNumW'] = $this->decode($data[$c]);
                                break;
                            case 8:
                                $this->insertArray['FioW'] = $this->decode($data[$c]);
                                break;
                            case 9:
                                $this->insertArray['CountryW'] = $this->decode($data[$c]);
                                break;
                            case 10:
                                $this->insertArray['ClubW'] = $this->decode($data[$c]);
                                break;
                            case 11:
                                $this->insertArray['CoachW'] = $this->decode($data[$c]);
                                $this->insertArray($this->table, $this->insertArray);
                                break;
                        }
                    }
                }
                fclose($handle);
                unlink($this->TXTuploadDir . $files['file']['name']);
                return true;
            }
        }
    }

    public function uploadPostDataRegionFile($files)
    {
        if ($this->getAll('SELECT * FROM ?n', 'regions')) {
            $this->query('TRUNCATE ?n', 'regions');
        }
        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['RegionName'] = $this->decode($data[$c]);
                                $this->insertArray('regions', $this->insertArray);
                                break;
                        }
                    }
                }
                fclose($handle);
                unlink($this->TXTuploadDir . $files['file']['name']);
                return true;
            }
        }
    }

    public function uploadPostDataClubsFile($files)
    {
        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                if(!$this->takeAllFromTableWhereEqually('clubs', 'ClubName',$this->decode($data[$c]))) {
                                    $this->insertArray['ClubName'] = $this->decode($data[$c]);
                                    $this->insertArray('clubs', $this->insertArray);
                                }
                                break;
                        }
                    }
                }
                fclose($handle);
                unlink($this->TXTuploadDir . $files['file']['name']);
                return true;
            }
        }
    }

    public function uploadPostDataCountryFile($files)
    {
        if ($this->getAll('SELECT * FROM ?n', 'countries')) {
            $this->query('TRUNCATE ?n', 'countries');
        }
        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['CountryNameEn'] = $this->decode($data[$c]);
                                break;
                            case 1:
                                $this->insertArray['CountryNameRu'] = $this->decode($data[$c]);
                                break;
                            case 2:
                                $this->insertArray['CountryNameUA'] = $this->decode($data[$c]);
                                break;
                            case 3:
                                $this->insertArray['CountryKod'] = $this->decode($data[$c]);
                                break;
                            case 4:
                                $this->insertArray['CountryFlag'] = $this->decode($data[$c]);
                                $this->insertArray('countries', $this->insertArray);
                                break;
                        }
                    }
                }
                fclose($handle);
                unlink($this->TXTuploadDir . $files['file']['name']);
                return true;
            }
        }
    }

    public function uploadPostDataCategoryFile($urlName, $files)
    {

        $this->table = $urlName . '_categories';

        if ($this->getAll('SELECT * FROM ?n', $this->table)) {
            $this->query('TRUNCATE ?n', $this->table);
        }
        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['CategoryName'] = $this->decode($data[$c]);
                                break;
                            case 1:
                                $this->insertArray['CategoryFileDraw'] = $this->decode($data[$c]);
                                break;
                            case 2:
                                $this->insertArray['Categorytatami'] = $data[$c];
                                $this->insertArray($this->table, $this->insertArray);
                                break;
                        }
                    }
                }
                fclose($handle);
                unlink($this->TXTuploadDir . $files['file']['name']);
                return true;
            }
        }
    }

    public function uploadPostDataCoachFile($files)
    {

        $this->table = 'coaches';

        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    if (isset($data[0]) && isset($data[3])) {
                        $this->coachUserNameArray[$i] = $data[0];
                        $this->coachClubArray[$i] = $data[3];
                    }
                    $i++;
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['UserName'] = $this->decode($data[$c]);
                                break;
                            case 1:
                                $this->insertArray['UserCountry'] = $this->decode($data[$c]);
                                break;
                            case 2:
                                $this->insertArray['UserRegion'] = $this->decode($data[$c]);
                                break;
                            case 3:
                                $this->insertArray['UserClub'] = $this->decode($data[$c]);

                                    // Переиспользование значения массива в отличии от Participant-метода!!!

                                $this->insertArray['UserRegion'] = $this->takeOneFromTableWhereEqually('RegionId', 'regions', 'RegionName', $this->insertArray['UserRegion']);
                                if ($this->fetch($this->query('SELECT * FROM ?n WHERE ?n = ?s AND ?n = ?s', $this->table, 'UserName', $this->insertArray['UserName'], 'UserClub', $this->insertArray['UserClub'])) === NULL) {
                                    $this->insertArray($this->table, $this->insertArray);
                                } else {
                                    $this->updateArray($this->table, array('UserName' => $this->insertArray['UserName'], 'UserCountry' => $this->insertArray['UserCountry'], 'UserCountry' => $this->insertArray['UserClub'], 'UserClub' => $this->insertArray['UserClub']), array('name' => 'UserName', 'str' => $this->insertArray['UserName']));
                                }
                                break;
                        }
                    }
                }
            }

            fclose($handle);
            unlink($this->TXTuploadDir . $files['file']['name']);

            if (!empty($this->takeAllFromTable($this->table))) {
                $array1 = $this->takeIdFromTable('UserName', $this->table);
                for ($t = 0; $t < count($array1); $t++) {
                    $array1[$t] = $array1[$t]['UserName'];
                }
                $array1Br = $this->takeIdFromTable('UserClub', $this->table);
                for ($l = 0; $l < count($array1Br); $l++) {
                    $array1Br[$l] = $array1Br[$l]['UserClub'];
                }
                $arrayBr = $this->coachClubArray;
                $array2 = $this->coachUserNameArray;
                for ($j = 0; $j < count($array1); $j++) {
                    // Для совпадений
                    $bool = false;
                    for ($k = 0; $k < count($array2); $k++) {
                        if ($array1[$j] == $this->decode($array2[$k]) && $array1Br[$j] == $this->decode($arrayBr[$k])) {
                            $bool = true;
                        }
                    }
                    if (!$bool) {
                        $this->query('DELETE FROM ?n WHERE ?n = ?s AND ?n = ?s', $this->table, 'UserName', $array1[$j], 'UserClub', $array1Br[$j]);
                    }
                }
                return true;
            }
        }
    }

    public function uploadPostDataparticipantsFile($urlName, $files)
    {

        $this->table = $urlName . '_participants';

        if (move_uploaded_file($files['file']['tmp_name'], $this->TXTuploadDir . basename($files['file']['name']))) {
            if (($handle = fopen($this->TXTuploadDir . basename($files['file']['name']), 'r')) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, 1000, ';')) !== FALSE) {
                    if (isset($data[0]) && isset($data[6])) {
                        $this->partFioArray[$i] = $data[0];
                        $this->partBrArray[$i] = $data[6];
                    }
                    $i++;
                    $num = count($data);
                    for ($c = 0; $c < $num; $c++) {
                        switch ($c) {
                            case 0:
                                $this->insertArray['FIO'] = $this->decode($data[$c]);
                                break;
                            case 1:
                                $this->insertArray['CountryName'] = $this->decode($data[$c]);
                                break;
                            case 2:
                                $this->insertArray['Region'] = $this->decode($data[$c]);
                                break;
                            case 3:
                                $this->insertArray['Club'] = $this->decode($data[$c]);
                                break;
                            case 4:
                                $this->insertArray['Coach'] = $this->decode($data[$c]);
                                break;
                            case 5:
                                $this->insertArray['Grade'] = $this->decode($data[$c]);
                                break;
                            case 6:
                                $this->insertArray['DateBr'] = date("Y-m-d",strtotime($data[$c]));
                                break;
                            case 7:
                                $this->insertArray['Photo'] = $this->decode($data[$c]);
                                break;
                            case 8:
                                $this->insertArray['Category'] = $this->decode($data[$c]);
                                break;
                            case 9:
                                $this->insertArray['Gender'] = $this->decode($data[$c]);
                                break;
                            case 10:
                                $this->insertArray['Weight'] = $data[$c];
                                break;
                            case 11:
                                $this->insertArray['Kumite'] = (bool) $data[$c];
                                break;
                            case 12:
                                $this->insertArray['Kata'] = (bool) $data[$c];
                                $this->insertArray['CategoryId'] = $this->takeOneFromTableWhereEqually('CategoryId', $urlName . '_categories', 'CategoryName', $this->insertArray['Category']);
                                $this->insertArray['CountryId'] = $this->takeOneFromTableWhereEqually('CountryId', 'countries', 'CountryNameEn', $this->insertArray['CountryName']);
                                $this->insertArray['ClubId'] = $this->takeOneFromTableWhereEqually('ClubId','clubs','ClubName',$this->insertArray['Club']);
                                unset($this->insertArray['Category']);
                                if ($this->fetch($this->query('SELECT * FROM ?n WHERE ?n = ?s AND ?n = ?s', $this->table, 'FIO', $this->insertArray['FIO'], 'DateBr', $this->insertArray['DateBr'])) === NULL) {
                                    $this->insertArray($this->table, $this->insertArray);
                                } else {
                                    $this->updateArray($this->table, array('CountryName' => $this->insertArray['CountryName'], 'Region' => $this->insertArray['Region'], 'Club' => $this->insertArray['Club'], 'ClubId' =>  $this->insertArray['ClubId'], 'Coach' => $this->insertArray['Coach'], 'Grade' => $this->insertArray['Grade'], 'Photo' => $this->insertArray['Photo'], 'DateBr' => $this->insertArray['DateBr'], 'Weight' => $this->insertArray['Weight'], 'Kumite' => $this->insertArray['Kumite'], 'Kata' => $this->insertArray['Kata'], 'CountryId' => $this->insertArray['CountryId'], 'Gender' => $this->insertArray['Gender'], 'CategoryId' => $this->insertArray['CategoryId']), array('name' => 'FIO', 'str' => $this->insertArray['FIO']));
                                }
                                break;
                        }
                    }
                }
            }

            // Тут немного каши в названиях переменных. Array1 = массив ФИО из БД. Array1Br = тоже выборка из БД, но по ДР. Array2 = массив ФИО из листа. ArrayBr = массив ДР из листа. $arrayBr = $this->partBrArray; $array2 = $this->partFioArray;

            
            fclose($handle);
            unlink($this->TXTuploadDir . $files['file']['name']);

            if (!empty($this->takeAllFromTable($this->table))) {
                $array1 = $this->takeIdFromTable('FIO', $this->table);
                for ($t = 0; $t < count($array1); $t++) {
                    $array1[$t] = $array1[$t]['FIO'];
                }
                $array1Br = $this->takeIdFromTable('DateBr', $this->table);
                for ($l = 0; $l < count($array1Br); $l++) {
                    $array1Br[$l] = $array1Br[$l]['DateBr'];
                }
                $arrayBr = $this->partBrArray;
                $array2 = $this->partFioArray;
                for ($j = 0; $j < count($array1); $j++) {
                    // Для поиска по совпадениям
                    $bool = false;
                    for ($k = 0; $k < count($array2); $k++) {
                        if ($array1[$j] == $this->decode($array2[$k]) && $array1Br[$j] == date("Y-m-d",strtotime($arrayBr[$k]))) {
                            $bool = true;
                        }
                    }
                    if (!$bool) {
                        $this->query('DELETE FROM ?n WHERE ?n = ?s AND ?n = ?s', $this->table, 'FIO', $array1[$j], 'DateBr', $array1Br[$j]);
                    }
                }
                return true;
            }
        }
    }

    public function uploadPostDataFilePng($urlName, $files)
    {
            $file = basename($files['file1']['name']);
            if (!move_uploaded_file($files['file1']['tmp_name'], $this->PNGuploadDir . $file)) {
                $handle = fopen($this->PNGuploadDir . 'ERROR_LOG.TXT', 'w');
                fwrite($handle, 'Can\'t move_uploaded_file! File name is: ' . $file);
                fclose($handle);
                exit();
            } else {

                $this->zip->open(ROOT.'/app/uploadedFiles/IMGfiles/Zip_File.zip');

                for ($i = 0; $i < $this->zip->numFiles; $i++) {
                    $filename = $this->zip->getNameIndex($i);
                    if(file_exists($urlName.'_'.strtolower($filename))) {
                        unlink($urlName.'_'.strtolower($filename));
                    }
                    $this->zip->renameName($filename,$urlName.'_'.strtolower($filename));
                }

                $this->zip->close();

                $this->zip->open(ROOT.'/app/uploadedFiles/IMGfiles/Zip_File.zip');

                $this->zip->extractTo(ROOT.'/app/uploadedFiles/IMGfiles/');

                $this->zip->close();

                unlink(ROOT.'/app/uploadedFiles/IMGfiles/Zip_File.zip');
            }
        return true;
    }

    public function uploadPostDataFilePngChamp($urlName, $files, $count)
    {
        for ($i = 1; $i <= $count; $i++) {
            if (file_exists($this->PNGuploadDir  . $urlName  .'_'. basename($files['file' . $i]['name']))) {
                unlink($this->PNGuploadDir . $urlName .'_'. basename($files['file' . $i]['name']));
            }
            if (!move_uploaded_file($files['file' . $i]['tmp_name'], $this->PNGuploadDir . $urlName  .'_'. basename($files['file' . $i]['name']))) {
                $handle = fopen($this->PNGuploadDir . 'ERROR_LOG.TXT', 'w');
                fwrite($handle, 'Can\'t move_uploaded_file! File name is: ' . basename($files['file' . $i]['name']));
                fclose($handle);
                exit();
            }
        }
        return true;
    }

}
