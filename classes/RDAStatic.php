<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * 
 */
class RDAStatic {

    public static function getPeopleCount($keys = false, $logic = "AND") {
        global $app;
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        $sql = "SELECT COUNT(*) AS doccnt FROM people  WHERE 1 ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "firstname": $sqlw[] = " firstname LIKE '%$value%' ";
                        break;
                    case "secondname": $sqlw[] = " secondname LIKE '%$value%' ";
                        break;
                    case "lastname": $sqlw[] = " lastname LIKE '%$value%' ";
                        break;
                    case "passport": $sqlw[] = " passport LIKE '%$value%' ";
                        break;
                }
            }
        if (!empty($sqlw))
            $sqlw = implode($logic, $sqlw);
        else
            $sqlw = "";
        if ($sqlw != "")
            $sql .= " AND " . $sqlw;
        $arRes = $app['db']->fetchAssoc($sql);
        return $arRes['doccnt'];
    }

    public static function getDocCount($keys = false, $logic = "AND", $search = false, $isarchive = false) {
        global $app;
        if ($isarchive)
            $view = 'document_archive';
        else
            $view = 'document_view';
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        $sql = "SELECT COUNT(*) AS doccnt FROM $view  WHERE 1 ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "type": $sqlw[] = " type = $value ";
                        break;
                }
            }
        if ($search) {

            $sqls = " (fullnum LIKE '%$search%' OR fullname LIKE '%$search%')";
        }
        if (!empty($sqlw))
            $sqlw = implode($logic, $sqlw);
        else
            $sqlw = "";
        if ($sqlw != "")
            $sql .= " AND " . $sqlw;

        if ($sqls != "")
            $sql .= " AND " . $sqls;
        $arRes = $app['db']->fetchAssoc($sql);
        return $arRes['doccnt'];
    }

    public static function getDocList($keys = array(), $logic = "AND", $limit = false, $search = false, $isarchive = false) {
        global $app;
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        if ($isarchive)
            $view = 'document_archive';
        else
            $view = 'document_view';
        $sql = "SELECT *, DATEDIFF(date_control,now()) AS `timetolife`  FROM $view WHERE 1 ";

        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "type": $sqlw[] = " type = $value ";
                        break;
                }
            }
        if ($search) {

            $sqls = " (fullnum LIKE '%$search%' OR fullname LIKE '%$search%')";
        }
        if (!empty($sqlw))
            $sqlw = implode($logic, $sqlw);
        else
            $sqlw = "";
        if ($sqlw != "")
            $sql .= " AND " . $sqlw;

        if ($sqls != "")
            $sql .= " AND " . $sqls;

        if (is_numeric($limit['start']) and is_numeric($limit['draw']) and is_numeric($limit['length'])) {
            $sql .= " LIMIT " . $limit['start'] . "," . $limit['length'] . " ";
        }
        $arPeople = $app['db']->fetchAll($sql);
        return $arPeople;
    }

    public static function getMaxNumDoc($type = false, $additional = false) {
        global $app;
        if (!$type)
            return false;
        $year = date("Y");
        $sql = "SELECT MAX(internal_number) AS max_num FROM `document` WHERE `year` = ? "
                . "AND `type` = ?";
        $arRes = $app['db']->fetchAssoc($sql, array((int) $year, $type));
        return (int) $arRes['max_num'];
    }

    /**
     * 
     * @global type $app
     * @param string $key
     * @param string $value
     * @return array
     */
    public static function getDocBy($pref1, $pref2, $intnum, $year) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document "
                . "INNER JOIN people ON document.topicstarter=people.id "
                . "WHERE year = $year AND internal_number = '$intnum'"
                . " AND num_prefix_1 = '$pref1'";

        $arDoc = $app['db']->fetchAssoc($sql);
        return $arDoc;
    }

    public static function getDocById($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document "
                . "INNER JOIN people ON document.topicstarter=people.id "
                . "WHERE id = '$id'";

        $arDoc = $app['db']->fetchAssoc($sql);
        return $arDoc;
    }

    /**
     * 
     * @global type $app
     * @param array $keys
     * @param string $logic
     * @return array
     */
    public static function getPeopleByAnyKey($keys = array(), $logic = "AND", $limit = "30") {
        global $app;
        $sqlw = array();
        $arPeople = array();
        $sql = "SELECT * FROM people_view WHERE 1 ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "firstname": $sqlw[] = " firstname LIKE '%$value%' ";
                        break;
                    case "secondname": $sqlw[] = " secondname LIKE '%$value%' ";
                        break;
                    case "lastname": $sqlw[] = " lastname LIKE '%$value%' ";
                        break;
                    case "passport": $sqlw[] = " passport LIKE '%$value%' ";
                        break;
                }
            }
        if (!empty($sqlw))
            $sqlw = implode($logic, $sqlw);
        else
            $sqlw = "";
        if ($sqlw != "")
            $sql .= " AND " . $sqlw;
        $arPeople = $app['db']->fetchAll($sql);
        return $arPeople;
    }

    
    public static function getStateDocTypes(){
        global $app;
        $arTypes=array();
        $sql = "SELECT * FROM state_types WHERE 1 ";
        $arTypes = $app['db']->fetchAll($sql);
        return $arTypes;
    }
}
