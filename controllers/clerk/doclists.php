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
    return $app['twig']->render('clerk.doclist.all.twig', $data);
})->bind('clerk.doclist.all');

$app->get('/clerk/doclist/all:{type}', function($type) use ($app) {
    $data=array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username']=$user->getName();
    $data['userid']=$user->getId();
    switch ($type){
        case "org": return $app['twig']->render('clerk.doclist.org.twig', $data); break; 
        case "state": return $app['twig']->render('clerk.doclist.state.twig', $data); break; 
        case "people": return $app['twig']->render('clerk.doclist.people.twig', $data); break; 
        case "visit": return $app['twig']->render('clerk.doclist.visitors.twig', $data); break; 
        default: return $app['twig']->render('clerk.doclist.all.twig', $data); break; 
    }
    return $app['twig']->render('clerk.doclist.all.twig', $data);
})->bind('clerk.doclist.alltyped');

/*$app->get('/clerk/doclist/archive', function() use ($app) {
    $data=array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username']=$user->getName();
    $data['userid']=$user->getId();
    
    
    
    return $app['twig']->render('clerk.doclist.archive.twig', $data);
})->bind('clerk.doclist.archive');
*/

$app->get('/clerk/docarchive/all:{type}', function($type) use ($app) {
    $data=array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username']=$user->getName();
    $data['userid']=$user->getId();
    switch ($type){
        //case "org": return $app['twig']->render('clerk.docarchive.org.twig', $data); break; 
        //case "state": return $app['twig']->render('clerk.docarchive.state.twig', $data); break; 
        case "people": return $app['twig']->render('clerk.docarchive.people.twig', $data); break; 
        case "visit": return $app['twig']->render('clerk.docarchive.visitors.twig', $data); break; 
        //default: return $app['twig']->render('clerk.doclist.all.twig', $data); break; 
    }
    return $app['twig']->render('clerk.doclist.all.twig', $data);
})->bind('clerk.docarchive.alltyped');

