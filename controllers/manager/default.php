<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/manager', function() use ($app) {
    return "itswork";
})->bind('manager.start');