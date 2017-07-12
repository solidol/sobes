<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Symfony\Component\HttpFoundation\JsonResponse;

$app->post('/ajax/org/getmaxnum', function() use ($app) {
    $result = array();
    $post = $app['request'];
    $key = $post->get('type');
    $result = RDAStatic::getMaxNumOrg($key);
    $resp = new JsonResponse($result);
    return $resp->setCallback(
                    $post->get('callback')
    );
})->bind('ajax.org.getmaxnum');

$app->get('/ajax/org/shortsearch', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('term'));
    $keys['name'] = $searchstring;
    $result = RDAStatic::getOrgByAnyKey($keys, "OR");
    foreach ($result as $k => $v) {
        $item['id'] = $v['id'];
        $item['label'] = $v['name'] . ' ' . $v['street'] . ', ' . $v['building'];
        $item['value'] = $v['name'] . ' ' . $v['street'] . ', ' . $v['building'];
        $arPeople[] = $item;
    }
    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.org.shortsearch');

$app->get('/ajax/org/getlist', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('search')['value']);
    $limit['draw'] = $get->get('draw')?$get->get('draw'):1;
    $limit['length'] = $get->get('length')?$get->get('length'):10;
    $limit['start'] = $get->get('start')?$get->get('start'):0;
    $search = $get->get('search')?$get->get('search')['value']:false;
    $keys = array();
    
    $keys['firstname'] = $searchstring;
    $keys['secondname'] = $searchstring;
    $keys['lastname'] = $searchstring;
    $keys['passport'] = $searchstring;
    $result = RDAStatic::getPeopleByAnyKey($keys, "OR");
    foreach ($result as $k => $v) {
        $item['lastname'] = $v['lastname'];
        $item['firstname'] = $v['firstname'];
        $item['secondname'] = $v['secondname'];
        $item['passport'] = $v['passport'];
        $item['addr'] = $v['street'].', б.'.$v['building'].', '.(($v['room']!='')?$v['room']:'');
        $arPeople['data'][] = $item;
    }
    $arPeople['draw']=$get->get('draw')?$get->get('draw'):false;
    $arPeople['recordsTotal']=RDAStatic::getPeopleCount();
    $arPeople['recordsFiltered']=RDAStatic::getPeopleCount($keys);
    
    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.org.getlist');

$app->post('/ajax/org/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $data['name'] = $post->get('orgname');
    $data['zipcode'] = $post->get('zipcode');
    $data['city'] = $post->get('city');
    $data['street'] = $post->get('street');
    $data['building'] = $post->get('building');
    $data['room'] = $post->get('room');
    $data['comment'] = $post->get('comment');

    $app['db']->insert('organizations', $data);


    return $app['db']->lastInsertId();
})->bind('ajax.org.push');

