<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'docsingle.php';
require_once 'doclists.php';
require_once 'people.php';

$app->get('/clerk', function() use ($app) {
    return $app['twig']->render('clerk.start.twig', array());
})->bind('clerk.start');

