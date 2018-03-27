<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$app->get('/ajax/document/shortsearch', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('term'));
    $keys['firstname'] = $searchstring;
    $keys['secondname'] = $searchstring;
    $keys['lastname'] = $searchstring;
    $keys['passport'] = $searchstring;
    $result = RDAStatic::getDocList($keys, "OR");
    foreach ($result as $k => $v) {
        $item['id'] = $v['id'];
        $item['label'] = $v['lastname'] . ' ' . $v['firstname'] . ' ' . $v['passport'];
        $item['value'] = $v['lastname'] . ' ' . $v['firstname'];
        $arPeople[] = $item;
    }
    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.shortsearch');

