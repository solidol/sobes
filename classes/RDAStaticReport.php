<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RDAStaticReport
 *
 * @author solidol
 */
class RDAStaticReport {

    //put your code here
    public static function getTotalByInterval($startDate, $endDate, $format = false, $filters = false) {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%d";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT 
                DATE_FORMAT(theDate,'$strFormat') as theDate, 
                count(DC.`id`) as num 
                FROM 
                (SELECT * FROM `document_view_people` $strFilter ) AS DC
                RIGHT JOIN
                dateIntervals AS DT
                on DC.`date_in`= DT.theDate
                GROUP BY date(DT.theDate) 
                order by DT.theDate ASC;";
        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

    public static function getTotalByIntervalAndExternals($startDate, $endDate, $format = false, $filters = false, $extType = 'externals') {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%d";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT 
                DATE_FORMAT(theDate,'$strFormat') as theDate, 
                count(DC.`id`) as num 
                FROM 
                ((SELECT * FROM `document_view_people` $strFilter ) AS DC
                INNER JOIN
                (SELECT * FROM `document_extnum` WHERE keystr = '$extType') AS EN
                on DC.`id` = EN.`document_id`)
                RIGHT JOIN
                dateIntervals AS DT
                on DC.`date_in`= DT.theDate
                GROUP BY date(DT.theDate) 
                order by DT.theDate ASC;";

        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

    public static function getTotalByIntervalInternal($startDate, $endDate, $format = false, $filters = false) {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%d";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT
                DATE_FORMAT(theDate,'$strFormat') as theDate,
                count(R.`id`) as num 
                FROM
                (SELECT * FROM 
                (SELECT * FROM `document_view_people` WHERE 1 ) AS DC
                LEFT JOIN
                (SELECT keystr, document_id FROM `document_extnum` ) AS EN
                on DC.`id` = EN.`document_id`  
                WHERE keystr is null OR keystr = 'РВК') AS R
                RIGHT JOIN
                dateIntervals AS DT
                on R.`date_in`= DT.theDate
                GROUP BY date(DT.theDate)
                order by DT.theDate ASC;";

        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

    //=====================  
    public static function getTotalByMonth($startDate, $endDate, $format = false, $filters = false) {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%m";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval_month( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT 
                DATE_FORMAT(theDate,'$strFormat') as theDate, 
                count(DC.`id`) as num 
                FROM 
                (SELECT * FROM `document_view_people` $strFilter ) AS DC
                RIGHT JOIN
                dateIntervals AS DT
                on DATE_FORMAT(DC.`date_in`,'%m')= DATE_FORMAT(DT.theDate,'%m')
                GROUP BY month(DT.theDate) 
                order by DT.theDate ASC;";
        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

    public static function getTotalByMonthAndExternals($startDate, $endDate, $format = false, $filters = false, $extType = 'externals') {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%m";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval_month( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT 
                DATE_FORMAT(theDate,'$strFormat') as theDate, 
                count(DC.`id`) as num 
                FROM 
                ((SELECT * FROM `document_view_people` $strFilter ) AS DC
                INNER JOIN
                (SELECT * FROM `document_extnum` WHERE keystr = '$extType') AS EN
                on DC.`id` = EN.`document_id`)
                RIGHT JOIN
                dateIntervals AS DT
                on DATE_FORMAT(DC.`date_in`,'%m')= DATE_FORMAT(DT.theDate,'%m')
                GROUP BY month(DT.theDate) 
                order by DT.theDate ASC;";

        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

    public static function getTotalByMonthInternal($startDate, $endDate, $format = false, $filters = false) {
        global $app;
        $strFilter = " WHERE 1 ";
        $strFormat = "%m";
        if ($startDate > $endDate)
            list($startDate, $endDate) = array($endDate, $startDate);
        $sql = "CALL date_interval_month( '$startDate', '$endDate');";
        $app['db']->fetchAssoc($sql);
        if ($filters) {
            $strFilter = " WHERE " . implode(' AND ', $filters) . " ";
        }
        if ($format) {
            $strFormat = "$format";
        }
        $sql = "SELECT
DATE_FORMAT(theDate,'$strFormat') as theDate,
count(R.`id`) as num 
FROM
(SELECT * FROM 

(SELECT * FROM `document_view_people` WHERE 1 ) AS DC
LEFT JOIN
(SELECT keystr, document_id FROM `document_extnum` ) AS EN
on DC.`id` = EN.`document_id`  

WHERE keystr is null OR keystr = 'РВК') AS R
RIGHT JOIN
dateIntervals AS DT
on DATE_FORMAT(R.`date_in`,'%m')= DATE_FORMAT(DT.theDate,'%m')

GROUP BY month(DT.theDate)
order by DT.theDate ASC;";

        $arResult = $app['db']->fetchAll($sql);
        return $arResult;
    }

}
