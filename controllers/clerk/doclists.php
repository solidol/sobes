<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/clerk/doclist/all', function() use ($app) {
    $data=array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username']=$user->getName();
    $data['userid']=$user->getId();
    
    
    
    return $app['twig']->render('clerk.doclist.twig', $data);
})->bind('clerk.doclist.all');

$app->get('/clerk/doclist/archive', function() use ($app) {
    $data=array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username']=$user->getName();
    $data['userid']=$user->getId();
    
    
    
    return $app['twig']->render('clerk.doclist.archive.twig', $data);
})->bind('clerk.doclist.archive');

