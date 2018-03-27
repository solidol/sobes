<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'docsingle.php';
require_once 'docpush.php';
require_once 'doclists.php';
require_once 'people.php';
require_once 'search.php';
require_once 'org.php';
require_once 'reports.php';

$app->get('/clerk', function() use ($app) {
    return $app->redirect($app['url_generator']->generate('clerk.doclist.all'));
})->bind('clerk.start');

