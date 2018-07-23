<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$app->post('/ajax/toarch/id:{doc}', function($doc) use ($app) {
    RDAStatic::moveToArchById($doc);
    return 'OK';
})->bind('ajax.doc.movetoarch');

$app->post('/ajax/remove/id:{doc}', function($doc) use ($app) {
    RDAStatic::removeDoc($doc);
    return 'OK';
})->bind('ajax.doc.remove');



$app->post('/ajax/resolution/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $userfrom = $user->getId();
    $userto = ($post->get('user') > 0) ? $post->get('user') : $user->getId();


    RDAStatic::changeDocStatus($post->get('doc'), 'hasresolution');
    RDAStatic::pushResolution($post->get('doc'), $userto, $post->get('textres'));

    return 'OK';
})->bind('ajax.resolution.push');

$app->post('/ajax/notes/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];


    RDAStatic::pushNotesByDocId($post->get('doc'), $post->get('text'), $post->get('notestype'));


    return 'OK';
})->bind('ajax.notes.push');

$app->post('/ajax/datescontrol/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];
    $arDates = $post->get('dates_control');
    $data['date_control'] = (is_array($arDates)) ? $arDates[0] : '00.00.0000';
    $app['db']->delete('dates_control', array('document'=>$post->get('doc')));
    foreach ($arDates as $dtItem) {
        $dates = array();
        $dates['document'] = $post->get('doc');
        $dates['date_control'] = ($dtItem > '') ? $dtItem : '00.00.0000';
        $app['db']->insert('dates_control', $dates);
    }
    return 'OK';
})->bind('ajax.datescontrol.push');

$app->post('/ajax/document/move', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $userfrom = ($post->get('userfrom') > 0) ? $post->get('userfrom') : $user->getId();
    $userto = ($post->get('usertomov') > 0) ? $post->get('usertomov') : $user->getId();


    RDAStatic::moveDoc($post->get('docid'), $userfrom, $userto, $post->get('textmov'));
    //}
    return 'OK';
})->bind('ajax.moving.push');

$app->post('/ajax/donestatus/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];
    $id = $post->get('docid');

    RDAStatic::pushNotesByDocId($id, $post->get('textstatus'), 'donestr');
    RDAStatic::setDoneStatusById($id, $post->get('donestat'));


    return 'OK';
})->bind('ajax.donestatus.push');

$app->post('/ajax/autosave/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];
    $id = $post->get('id');
    $content = strip_tags($post->get('content'));
    $params = explode(":", $id);
    switch ($params[2]) {
        case "date_in":
        case "date_control":
            $dt = explode(".", $content);
            $content = $dt[2] . '-' . $dt[1] . '-' . $dt[0];
            break;
    }


    switch ($params[1]) {
        case "mainfield":
            RDAStatic::updateMainField($params[0], $params[2], $content);
            break;
        case "metafield":
            RDAStatic::updateMetaField($params[0], $params[2], $content);
            break;
    }
    return $post->get('content');
})->bind('ajax.autosave.push');
