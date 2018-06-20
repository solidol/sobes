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

    public static function getDatesByDocId($docId) {
        global $app;
        if ($docId < 1)
            return false;
        $sql = "SELECT * FROM dates_control_view WHERE document = ?";
        $arRes = $app['db']->fetchAll($sql, array((int) $docId));
        return $arRes;
    }

    public static function updateMainField($docid, $fieldname, $content) {
        global $app;
        if ($docid < 1)
            return false;
        $result = $app['db']->update('document', array($fieldname => $content), array('id' => $docid));

        return 'OK';
    }

    public static function updateMetaField($fieldid, $fieldname, $content) {
        global $app;
        if ($fieldid < 1)
            return false;
        $sql = "SELECT * FROM document_$fieldname WHERE id = ?";


        $arRes = $app['db']->fetchAssoc($sql, array((int) $fieldid));
        $values = unserialize($arRes['value']);
        $values['fullstr'] = $content;
        $content = serialize($values);
        $result = $app['db']->update('document_' . $fieldname, array('value' => $content), array('id' => $fieldid));

        return 'OK';
    }

    public static function getManagers($andBoss = false) {
        global $app;
        $sql = "SELECT * FROM users WHERE roles LIKE '%ROLE_MANAGER%'";

        if ($andBoss)
            $sql .= " OR roles LIKE '%ROLE_BOSS%'";
        $arRes = $app['db']->fetchAll($sql);
        return $arRes;
    }

    public static function getByRoles($roles = array("ROLE_USER")) {
        global $app;

        foreach ($roles as &$rItem) {
            $rItem = " roles LIKE '%$rItem%' ";
        }
        $roles = implode(' OR ', $roles);
        $sql = "SELECT * FROM users WHERE $roles";

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

    public static function getDocList($keys = array(), $logic = "AND", $limit = false, $search = false, $isarchive = false, $donestr = false) {
        global $app;
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        if ($isarchive)
            $view = 'document_archive';
        else
            $view = 'document_work';
        if (isset($keys['type']))
            $view .= '_' . $keys['type'];
        $sql = "SELECT *, DATEDIFF(date_control,now()) AS `timetolife`  FROM $view WHERE 1 ";
        if ($donestr){
            $sql = "SELECT *, DATEDIFF(date_control,now()) AS `timetolife`  FROM $view "
                    . "LEFT JOIN "
                    . "(SELECT document_id, GROUP_CONCAT(donetext separator '<br>\n') AS donestr "
                    . "from `donestrings_view` group by document_id) AS dones "
                    . "ON $view.id = dones.document_id "
                    . "WHERE 1 ";
        }
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                if ($value > '')
                    switch ($key) {
                        case "type":
                            if ($value == 'people')
                                $type = " (type = 'people' OR type = 'visitors') ";
                            else
                                $type = " type = '$value' ";
                            $sqlw[] = $type;
                            break;
                        case "fullnum": $sqlw[] = " fullnum LIKE '%$value%' ";
                            break;
                        case "fullname": $sqlw[] = " fullname LIKE '%$value%' ";
                            break;
                        case "date_control": $sqlw[] = " date_control = '$value' ";
                            break;
                        case "date_in_text": $sqlw[] = " date_in_text LIKE '%$value%' ";
                            break;
                        case "date_control_text": $sqlw[] = " date_control_text LIKE '%$value%' ";
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
            $sql .= " ORDER BY id DESC ";
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
        if ($type == 'people' or $type == 'visitors')
            $type = " (`type` = 'people' OR type = 'visitors') ";
        else
            $type = " `type` = '$type' ";
        $sql = "SELECT MAX(internal_number) AS max_num FROM `document` WHERE `year` = ? "
                . "AND $type";
        $arRes = $app['db']->fetchAssoc($sql, array((int) $year));
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

    public static function getDocById($id, $isarchive = false) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document WHERE document.id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));

        if (!$isarchive)
            $view = 'document_view_' . $arDoc['type'];
        else
            $view = 'document_archive_' . $arDoc['type'];



        $sql = "SELECT * FROM $view WHERE id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));

        if ($arDoc['type'] == 'people') {
            $arDoc['peoples'] = RDAStaticPeople::getPeoplesIdByDoc($id);
            foreach ($arDoc['peoples'] as &$item) {
                $item['full'] = RDAStatic::getPeopleByAnyKey(array('id' => $item['people_id']));
            }
        } else
            $arDoc['peoples'] = array();
        return $arDoc;
    }

    public static function getArchDocById($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document WHERE document.id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));
        $view = 'document_archive_' . $arDoc['type'];

        $sql = "SELECT * FROM $view WHERE id = ?";

        $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));

        if ($arDoc['type'] == 'people') {
            $arDoc['peoples'] = RDAStaticPeople::getPeoplesIdByDoc($arDoc['id']);
            foreach ($arDoc['peoples'] as &$item) {
                $item['full'] = RDAStatic::getPeopleByAnyKey(array('id' => $item['people_id']));
            }
        } else
            $arDoc['peoples'] = array();
        return $arDoc;
    }

    public static function getNotesByDocId($id, $type = 'notes') {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document_$type WHERE document_id = ? AND `keystr` = '$type' ORDER BY id ASC";
        //var_dump($sql);
        $res = array();
        $arDoc = $app['db']->fetchAll($sql, array((int) $id));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();


        return $arDoc;
    }

    public static function pushNotesByDocId($docId = 0, $note = '', $type = 'notes') {
        global $app;
        $arDoc = '';
        $token = $app['security']->getToken();
        $user = $token->getUser();

        $value['userid'] = $user->getId();
        $userFrom = $app['user.manager']->getUser($user->getId());

        $txtvalue['userfrom'] = $userFrom->getName();

        $value['userfrom'] = $user->getId();

        $value['text'] = $note;
        $value['date'] = date('d.m.Y');

        switch ($type) {
            case 'notes': $typetext = 'Внутрішня відмітка: ';
                $tab = 'notes';
                break;
            case 'donestr': $typetext = 'Відмітка про виконання: ';
                $tab = 'donestr';
                break;
            default: $typetext = 'Відмітка ';
                $tab = 'notes';
        }
        $typetext = ''; // АЗАЗАЗАЗАЗАЗАЗАЗАЗА
        //$value['fullstr'] = date('d.m.Y') . ' - ' . $typetext .
        $value['fullstr'] = $typetext .
                $value['text'];
        $data['document_id'] = $docId;
        $data['keystr'] = $tab;
        $data['value'] = serialize($value);
        $app['db']->insert('document_' . $tab, $data);




        return 'OK';
    }

    public static function moveToArchById($id) {
        global $app;
        $arDoc = array();
        $sql = "UPDATE document SET status = 'archived' WHERE id = ?";

        $arDoc = $app['db']->executeUpdate($sql, array((int) $id));
        return 0;
    }

    public static function removeDoc($id) {
        global $app;
        $arDoc = array();
        
        $app['db']->delete('document', array('id' => (int) $id));

        return 0;
    }

    public static function setDoneStatusById($id, $status = 'err') {
        global $app;
        $arDoc = array();
        $sql = "UPDATE document SET donestatus = ? WHERE id = ?";

        $arDoc = $app['db']->executeUpdate($sql, array($status, (int) $id));
        return 0;
    }

    /**
     * 
     * @global type $app
     * @param array $keys
     * @param string $logic
     * @return array
     */
    public static function getPeopleByAnyKey($keys = array(), $logic = "AND", $limit = array()) {
        global $app;
        $sqlw = array();
        $arPeople = array();
        $sql = "SELECT * FROM people_view WHERE id > '0' ";
        if (!empty($keys))
            foreach ($keys as $key => $value) {
                if ($value != '')
                    switch ($key) {

                        case "id": $sqlw[] = " id = '$value' ";
                            break;
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
        $sql .= " ORDER BY lastname  COLLATE  utf8_unicode_ci ASC";
        if (!empty($limit))
            if (is_numeric($limit['start']) and is_numeric($limit['length'])) {
                $sql .= " LIMIT " . $limit['start'] . "," . $limit['length'] . " ";
            }
        $arPeople = $app['db']->fetchAll($sql);
        //var_dump($sql);
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

    public static function getResolutionByDocId($docId) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM movings_view WHERE document = ?";

        $sql = "SELECT * FROM document_movings WHERE document_id = ? and keystr='resolution' ORDER BY id ASC";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $docId));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();


        return $arDoc;
    }

    public static function moveDoc($docId, $from, $to, $text) {
        global $app;
        $token = $app['security']->getToken();
        $user = $token->getUser();
        $sql = "SELECT * FROM document WHERE id = $docId";
        $res = $app['db']->fetchAssoc($sql);
        //$from = $res['curr_user'];
        $current_users = unserialize($res['curr_user']);

        $value['userid'] = $user->getId();
        $userFrom = $app['user.manager']->getUser($from);
        $userTo = $app['user.manager']->getUser($to);
        $txtvalue['userfrom'] = $userFrom->getName();
        $txtvalue['userto'] = $userTo->getName();
        $value['userfrom'] = $from;
        $value['userto'] = $to;
        $value['text'] = $text;
        $value['date'] = date('d.m.Y');
        $value['fullstr'] = date('d.m.Y') . ' - Передача документу з ' .
                $txtvalue['userfrom'] . ' на ' . $txtvalue['userto'] .
                ' Передано з приміткою: ' . $value['text'];
        $data['document_id'] = $docId;
        $data['keystr'] = 'moving';
        $data['value'] = serialize($value);
        $app['db']->insert('document_movings', $data);
        if ($app['db']->lastInsertId() > 0) {
            $current_users[] = $value['userto'];
            $app['db']->update('document', array('curr_user' => serialize($current_users)), array('id' => $docId));
        }
        return 0;
    }

    public static function getMovingsByDocId($docId) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM movings_view WHERE document = ?";

        $sql = "SELECT * FROM document_movings WHERE document_id = ? ORDER BY id ASC";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $docId));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();


        return $arDoc;
    }

    public static function pushExternalsByDocId($docId = 0, $arExternals = array()) {
        global $app;
        if (empty($arExternals))
            return false;
        //var_dump($arExternals);
        //die('');
        foreach ($arExternals as &$item) {
            if ($item['date'] == '' or $item['date'] == null)
                $item['date'] = date('d.m.Y');
            $item['fullstr'] = $item['date'] . ' надійшов з ' . $item['org'] .
                    ' під номером ' . $item['number'];
            $data['document_id'] = $docId;
            if (stristr($item['org'], 'Обл'))
                $item['org'] = 'Облрада';
            switch (true) {
                case mb_stristr($item['org'], 'Мінсоцполітики'): $keystr = 'Мінсоцполітики';
                    break;
                case mb_stristr($item['org'], 'з НС'): $keystr = 'ДСНС';
                    break;
                case mb_stristr($item['org'], 'ДСНС'): $keystr = 'ДСНС';
                    break;
                case mb_stristr($item['org'], 'ОДА'): $keystr = 'ОДА';
                    break;
                case mb_stristr($item['org'], 'ВРУ'): $keystr = 'ВРУ';
                    break;
                case mb_stristr($item['org'], 'Верховна Рада'): $keystr = 'ВРУ';
                    break;
                case mb_stristr($item['org'], 'КМУ'): $keystr = 'КМУ';
                    break;
                case mb_stristr($item['org'], 'Кабінет міністрів'): $keystr = 'КМУ';
                    break;
                case mb_stristr($item['org'], 'кабмін'): $keystr = 'КМУ';
                    break;
                case mb_stristr($item['org'], 'Облрада'): $keystr = 'Облрада';
                    break;
                case mb_stristr($item['org'], 'МВК'): $keystr = 'МВК';
                    break;
                default: $keystr = 'externals';
            }
            $data['keystr'] = $keystr;
            $data['value'] = serialize($item);


            $result = $app['db']->insert('document_extnum', $data);
        }



        return 'OK';
    }

    public static function getExternalsByDocId($id) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM document_extnum WHERE document_id = ?  ";

        $res = false;
        $arDoc = $app['db']->fetchAll($sql, array((int) $id));
        if (is_null($arDoc) or empty($arDoc))
            $arDoc = array();


        return $arDoc;
    }

    /*
      public static function getOthersTsByDocId($id) {
      global $app;
      $arDoc = array();
      $sql = "SELECT * FROM other_people_view WHERE document_id = ? AND `keystr` = 'others' ";

      $res = false;
      $arDoc = $app['db']->fetchAssoc($sql, array((int) $id));
      if ($arDoc['value'])
      $res = $arDoc['value'];
      else
      $res = "";
      return $res;
      }
     * */

    public static function pushResolution($docId, $to, $text) {
        global $app;
        $token = $app['security']->getToken();
        $user = $token->getUser();
        $sql = "SELECT * FROM document WHERE id = $docId";
        $res = $app['db']->fetchAssoc($sql);
        //$from = $res['curr_user'];
        $current_users = unserialize($res['curr_user']);

        $value['userid'] = $user->getId();
        $userFrom = $app['user.manager']->getUser($user->getId());
        $userTo = $app['user.manager']->getUser($to);
        $txtvalue['userfrom'] = $userFrom->getName();
        $txtvalue['userto'] = $userTo->getName();
        $value['userfrom'] = $from;
        $value['userto'] = $to;
        $value['text'] = $text;
        $value['date'] = date('d.m.Y');
        //$value['fullstr'] = date('d.m.Y') . ' - Резолюція до виконання на ' .
        $value['fullstr'] = 'Резолюція до виконання на ' .
                $txtvalue['userto'] .
                ' Передано з приміткою: ' . $value['text'];
        $data['document_id'] = $docId;
        $data['keystr'] = 'resolution';
        $data['value'] = serialize($value);
        $app['db']->insert('document_movings', $data);
        if ($app['db']->lastInsertId() > 0) {
            $current_users[] = $value['userto'];
            $app['db']->update('document', array('curr_user' => serialize($current_users)), array('id' => $docId));
        }
        return 0;
    }

    public static function getOrgMonthReport($startDate = 'now', $endDate = 'now') {
        global $app;
        $sqlw = array();
        $sqls = '';
        $arPeople = array();
        if ($startDate == 'now' or $endDate == 'now')
            $sql = "SELECT *, DATEDIFF(date_control,now()) AS `timetolife`  FROM document_work_org "
                    . "WHERE DATE_FORMAT(NOW(),'%m-%Y') =   DATE_FORMAT(date_control,'%m-%Y')";
        else
            $sql = "SELECT *, DATEDIFF(dw.date_control,now()) AS `timetolife` "
                    . " FROM document_work_org AS dw  INNER JOIN dates_control AS dc ON dw.id=dc.document "
                    . " WHERE DATE_FORMAT('$startDate','%Y-%m') <=   DATE_FORMAT(dc.date_control,'%Y-%m') AND "
                    . " DATE_FORMAT('$endDate','%Y-%m') >=   DATE_FORMAT(dc.date_control,'%Y-%m')";

        $arPeople = $app['db']->fetchAll($sql);
        //var_dump($sql);
        return $arPeople;
    }

    
    public static function getDoneDtringsConcated($doc){
        $sql = "SELECT document_id, GROUP_CONCAT(donetext separator '<br>\n') as donestr
            from 
            `donestrings_view` 
            where document_id= $doc 
            group by document_id";
        $arPeople = $app['db']->fetchAssoc($sql);
    }
}
