<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/clerk/people/all', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();



    return $app['twig']->render('clerk.peoplelist.twig', $data);
})->bind('clerk.people.all');


$app->post('/clerk/newpeople', function() use ($app) {
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
    $data['street'] = $post->get('stype').' '.$post->get('street');
    $data['building'] = $post->get('building');
    $data['room'] = $post->get('room');
    $data['comment'] = $post->get('comment');
    $data['sex'] = $post->get('sex');
    $phones[] = $post->get('homephone');
    $phones[] = $post->get('mobilephone');
    $data['phones'] = implode(',',$phones);
    $app['db']->insert('people', $data);
    $newId = $app['db']->lastInsertId();
    $arMetaSoc = array();
    foreach ($post->get('social') as $k=>$v){
        $arMetaSoc[]=$k;
    }
    RDAStaticPeople::pushPeopleMetaByPeopleId($newId, $arMetaSoc);
    return $app->redirect(
                    $app['url_generator']->generate('clerk.people.all'));
})->bind('clerk.people.push');

$app->get('/clerk/people/add', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['peopleattr'] = RDAStaticPeople::getSocialStatuses();
    
    $data['id']='';
    $data['lastname']='';
    $data['firstname']='';
    $data['secondname']='';
    $data['sex']='';
    $data['passport']='';
    $data['zipcode']='';
    $data['city']='';
    $data['building']='';
    $data['housing']='';
    $data['room']='';
    $data['phones']='';
    return $app['twig']->render('clerk.newpeople.twig', $data);
})->bind('clerk.people.add');

$app->get('/clerk/people/edit:{pid}', function($pid) use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['peopleattr'] = RDAStaticPeople::getSocialStatuses();
    
    
    $data['data']=RDAStatic::getPeopleByAnyKey(array('id'=>$pid))[0] ;
    
    return $app['twig']->render('clerk.newpeople.twig', $data);
})->bind('clerk.people.edit');



