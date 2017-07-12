<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\HttpFoundation\JsonResponse;

$app->get('/ajax/people/shortsearch', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('term'));
    $keys['firstname'] = $searchstring;
    $keys['secondname'] = $searchstring;
    $keys['lastname'] = $searchstring;
    $keys['passport'] = $searchstring;
    $result = RDAStatic::getPeopleByAnyKey($keys, "OR");
    foreach ($result as $k => $v) {
        $item['id'] = $v['id'];
        $item['label'] = $v['lastname'] . ' ' . $v['firstname'] . ' ' . $v['secondname'] .
                ' | ' . $v['street'] . ', ' . $v['building'] . ', кв.' . $v['room'];
        $item['value'] = $v['lastname'] . ' ' . $v['firstname'] . ' ' . $v['secondname'];
        $arPeople[] = $item;
    }
    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.people.shortsearch');

$app->get('/ajax/people/getlist', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('search')['value']);
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
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
        $item['addr'] = $v['street'] . ', б.' . $v['building'] . ', ' . (($v['room'] != '') ? $v['room'] : '');
        $arPeople['data'][] = $item;
    }
    $arPeople['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arPeople['recordsTotal'] = RDAStatic::getPeopleCount();
    $arPeople['recordsFiltered'] = RDAStatic::getPeopleCount($keys);

    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.people.getlist');

$app->post('/ajax/people/push', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $data['firstname'] = $post->get('firstname');
    $data['secondname'] = $post->get('secondname');
    $data['lastname'] = $post->get('lastname');
    $data['passport'] = $post->get('passport');
    $data['zipcode'] = $post->get('zipcode');
    $data['city'] = $post->get('city');
    $data['street'] = $post->get('street');
    $data['building'] = $post->get('building');
    $data['room'] = $post->get('room');
    $data['comment'] = $post->get('comment');

    $app['db']->insert('people', $data);


    return $app['db']->lastInsertId();
})->bind('ajax.people.push');
