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
    $data['street'] = $post->get('street');
    $data['building'] = $post->get('building');
    $data['room'] = $post->get('room');
    $data['comment'] = $post->get('comment');

    $app['db']->insert('people', $data);


    return $app->redirect(
                    $app['url_generator']->generate('clerk.doc.view', array('year' => date("Y"),
                        'pref1' => $data['num_prefix_1'],
                        'pref2' => $data['num_prefix_1'],
                        'no' => $data['internal_number'])));
})->bind('clerk.people.push');



