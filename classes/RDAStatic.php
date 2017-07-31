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

    public static function getManagers() {
        global $app;
        $sql = "SELECT * FROM users WHERE roles LIKE '%ROLE_MANAGER%'";
        $arRes = $app['db']->fetchAll($sql);
        return $arRes;
    }

    public static function getBoss() {
        global $app;
        $sql = "SELECT * FROM users WHERE roles LIKE '%ROLE_BOSS%'";
        $arRes = $app['db']->fetchAssoc($sql);
        return $arRes;
    }

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

    public static function getOrgCount($keys = false, $logic = "AND") {
        global $app;
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        $sql = "SELECT COUNT(*) AS doccnt FROM organizations  WHERE 1 ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "name": $sqlw[] = " name LIKE '%$value%' ";
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
                    case "type": $sqlw[] = " type = '$value' ";
                        break;
                    case "date_control": $sqlw[] = " date_control = '$value' ";
                        break;
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
        if (isset($keys['type']) AND $view != 'document_archive')
            $view .= '_' . $keys['type'];
        $sql = "SELECT *, DATEDIFF(date_control,now()) AS `timetolife`  FROM $view WHERE 1 ";

        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "type": $sqlw[] = " type = '$value' ";
                        break;
                    case "fullnum": $sqlw[] = " fullnum LIKE '%$value%' ";
                        break;
                    case "date_in": $sqlw[] = " date_in = '$value' ";
                        break;
                    case "date_control": $sqlw[] = " date_control = '$value' ";
                        break;
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
        //var_dump($sql);
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
        $sql = "SELECT * FROM document WHERE document.id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));
        $view = 'document_view_' . $arDoc['type'];

        $sql = "SELECT * FROM $view WHERE id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));
        return $arDoc;
    }

    public static function getNotesByDocId($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document_notes_view WHERE document_id = ? AND `keystr` = 'notes' ";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $id));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();
        else {
            $arDoc = unserialize($arDoc[0]['value']);
            $res = array();
            foreach ($arDoc as $key => $value)
                $res[]['value'] = $value;
        }

        return $res;
    }

    public static function pushNotesByDocId($id = 0, $arNotes = array()) {
        global $app;
        $arDoc = 0;
        $arNotes = array_diff($arNotes, array(''));
        /* foreach ($arNotes as &$item){
          $new['date'] = date('d.m.Y');
          $new['data'] = $item;
          $item = $new;

          } */
        $notes = serialize($arNotes);

        $result = $app['db']->update('document_meta', array('value' => $notes), array('document_id' => $id, 'keystr' => "notes"));
        if (!$result)
            $app['db']->insert('document_meta', array('keystr' => 'notes', 'value' => $notes, 'document_id' => $id));
        return $result;
    }

    public static function moveToArchById($id) {
        global $app;
        $arDoc = array();
        $sql = "UPDATE document SET status = 'archived' WHERE id = ?";

        $arDoc = $app['db']->executeUpdate($sql, array((int) $id));
        return 0;
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

    public static function getOrgByAnyKey($keys = array(), $logic = "AND", $limit = "30") {
        global $app;
        $sqlw = array();
        $arPeople = array();
        $sql = "SELECT * FROM organizations WHERE 1 ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                switch ($key) {
                    case "name": $sqlw[] = " name LIKE '%$value%' ";
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

    public static function getStateDocTypes() {
        global $app;
        $arTypes = array();
        $sql = "SELECT * FROM state_types WHERE subcode > 0 ";
        $arTypes = $app['db']->fetchAll($sql);
        return $arTypes;
    }

    public static function getOrgDocTypes() {
        global $app;
        $arTypes = array();
        $sql = "SELECT * FROM org_types WHERE subcode > 0 ";
        $arTypes = $app['db']->fetchAll($sql);
        return $arTypes;
    }

    public static function getContentDocTypes() {
        global $app;
        $arTypes = array();
        $sql = "SELECT * FROM content_types WHERE subcode > 0 ";
        $arTypes = $app['db']->fetchAll($sql);
        return $arTypes;
    }

    public static function getMaxNumOrg($type = false) {
        global $app;
        if (!$type)
            return false;
        $sql = "SELECT MAX(internal_number) AS max_num FROM `document` WHERE `num_prefix_0` = ? ";
        $arRes = $app['db']->fetchAssoc($sql, array($type));
        return ($arRes['max_num']) ? (int) $arRes['max_num'] : 0;
    }

    public static function changeDocStatus($doc, $status = 'created') {
        global $app;
        $result = $app['db']->update('document', array('status' => $status), array('id' => $doc));
        return $result;
    }

    public static function getResolutionByDocId($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document_resolution_view WHERE document_id = ? AND `keystr` = 'resolution' ";

        $res = false;
        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();
        else {
            $arDoc = unserialize($arDoc['value']);
        }

        return $arDoc;
    }
   
    public static function moveDoc($docId, $from, $to, $text){
        global $app;
        if ($from==false) {
            $sql = "SELECT * FROM document WHERE id = $docId";
            $res = $app['db']->fetchAssoc($sql);
            $from = $res['curr_user'];
        }
        $app['db']->insert('movings',array('document'=>$docId,
            'prevuser'=>$from,
            'nextuser'=>$to,
            'movtext'=>$text));
        
        if ($app['db']->lastInsertId()>0){
            $app['db']->update('document',array('curr_user'=>$to),array('id'=>$docId));
        }
        return 0;
    }

    public static function getMovingsByDocId($docId){
                global $app;
        $arDoc = array();
        $sql = "SELECT * FROM movings_view WHERE document = ?";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $docId));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();
        

        return $arDoc;
    }
    
    public static function pushExternalsByDocId($id = 0, $arExternals = array()) {
        global $app;
        if (empty($arExternals)) return false;
        $arDoc = 0;
        foreach($arExternals as &$item){
            if ($item['date']=='' or $item['date']==null) $item['date']=date('d.m.Y');
        }
        $externals = serialize($arExternals);

        $result = $app['db']->update('document_meta', array('value' => $externals), array('document_id' => $id, 'keystr' => "externals"));
        if (!$result)
            $app['db']->insert('document_meta', array('keystr' => 'notes', 'externals' => $externals, 'document_id' => $id));
        return $result;
    }

    public static function getExternalsByDocId($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document_notes_view WHERE document_id = ? AND `keystr` = 'externals' ";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $id));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();
        else {
            $arDoc = unserialize($arDoc[0]['value']);
            
        }

        return $arDoc;
    }

    
}
