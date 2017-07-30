<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//require_once 'catalog.php';
//require_once 'single.php';


$app->get('/admin', function() use ($app) {
    return $app->redirect($app['url_generator']->generate('clerk.start'));
})->bind('admin.start');
