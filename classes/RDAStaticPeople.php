<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RADStaticPeople
 *
 * @author solidol
 */
class RDAStaticPeople {

    //put your code here
    public static function getSocialStatuses() {
        global $app;
        $sql = "SELECT * FROM social_status";
        $arDoc = $app['db']->fetchAll($sql);
        return $arDoc;
    }

    public static function getSocialStatusesByPeopleId($id) {
        global $app;
        $sql = "SELECT * FROM social_status";
        $arAllStatuses = $app['db']->fetchAll($sql);
        $sql = "SELECT * FROM people_meta WHERE people_id = $id AND keystr = 'social'";
        $arKeys = $app['db']->fetchAssoc($sql);
        $arKeys = unserialize($arKeys['value']);
        //var_dump($arKeys);
        $arRes = array();
        if (is_array($arAllStatuses) and is_array($arKeys))
            foreach ($arAllStatuses as $value) {
                //if ($value['keystr']=="geroyukrainy") var_dump ($value);

                if (in_array($value['keystr'], $arKeys)) {
                    //var_dump($value);
                    $arRes[] = $value;
                }
            } else
            $arRes = array();

        return $arRes;
    }

    public static function getPeopleMetaByPeopleId($id, $metaType) {
        global $app;
        $arDoc = array();
        $sql = "SELECT * FROM people_meta WHERE people_id = ? AND `keystr` = '?' ";
        //var_dump($sql);
        $res = array();
        $arDoc = $app['db']->fetchAll($sql, array((int) $id), $metaType);
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

    public static function pushPeopleMetaByPeopleId($id = 0, $arNotes = array(), $type = 'social') {
        global $app;
        $arDoc = 0;
        $arNotes = array_diff($arNotes, array(''));
        $notes = serialize($arNotes);

        $result = $app['db']->update('people_meta', array('value' => $notes), array('people_id' => $id, 'keystr' => $type));
        if (!$result)
            $app['db']->insert('people_meta', array('keystr' => $type, 'value' => $notes, 'people_id' => $id));
        return $result;
    }

    public static function insertPeoplesDoc($docId = 0, $peoples = array()) {
        global $app;
        $query = array();
        foreach ($peoples as $item) {
            $ny['people_id'] = $item;
            $ny['document_id'] = $docId;
            $app['db']->insert('document_people', $ny);
        }
    }

    public static function getPeoplesIdByDoc($docId) {
        global $app;
        $arRes = array();
        $sql = "SELECT people_id FROM document_people WHERE document_id = ?";
        //var_dump($sql);
        $arRes = $app['db']->fetchAll($sql, array((int) $docId));
        return $arRes;
    }

}
