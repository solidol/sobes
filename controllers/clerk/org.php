<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/clerk/org/all', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();



    return $app['twig']->render('clerk.orglist.twig', $data);
})->bind('clerk.org.all');


$app->post('/clerk/neworg', function() use ($app) {
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


    return $app->redirect(
                    $app['url_generator']->generate('clerk.org.all'));
})->bind('clerk.org.push');

$app->get('/clerk/org/add', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();



    return $app['twig']->render('clerk.neworg.twig', $data);
})->bind('clerk.org.add');



