<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/clerk/newdoc/people', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['new_num'] = RDAStatic::getMaxNumDoc("people") + 1;
    return $app['twig']->render('clerk.newdoc.people.twig', $data);
})->bind('clerk.doc.new.people');

$app->get('/clerk/newdoc/org', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();

    return $app['twig']->render('clerk.newdoc.org.twig', $data);
})->bind('clerk.doc.new.org');

$app->get('/clerk/newdoc/state', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['new_num'] = RDAStatic::getMaxNumDoc("state") + 1;
    $data['doctypes'] = RDAStatic::getStateDocTypes();
    return $app['twig']->render('clerk.newdoc.state.twig', $data);
})->bind('clerk.doc.new.state');

$app->post('/clerk/newdoc', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $dt_in = explode(".", $post->get('date_in'));
    $dt_in = $dt_in[2] . '-' . $dt_in[1] . '-' . $dt_in[0];
    $dt_cr = explode(".", $post->get('date_control'));
    $dt_cr = $dt_cr[2] . '-' . $dt_cr[1] . '-' . $dt_cr[0];

    $data['num_prefix_1'] = $post->get('num_prefix_1');
    $data['num_prefix_2'] = $post->get('num_prefix_2');
    $data['internal_number'] = $post->get('internal_number');
    $data['external_number'] = $post->get('external_number');
    $data['year'] = (int) date("Y");
    $data['date_create'] = date("Y-m-d");
    $data['date_in'] = $dt_in;
    $data['date_control'] = $dt_cr;
    $data['type'] = $post->get('doctype');
    $data['topicstarter'] = $post->get('topicstarter')?$post->get('topicstarter'):0;
    $data['created_by'] = $user->getId();
    $data['summary'] = $post->get('summary');
    $data['comment'] = $post->get('comment');

    $app['db']->insert('document', $data);
    
switch ($post->get('doctype')){
    case "people": return $app->redirect(
                    $app['url_generator']->generate('clerk.doc.view', array('id' => date("Y"),
                        'pref1' => $data['num_prefix_1'],
                        //'pref2' => $data['num_prefix_1'],
                        'no' => $data['internal_number']))); break;
    case "state": return $app->redirect(
                    $app['url_generator']->generate('clerk.doc.view', array('year' => date("Y"),
                        'pref1' => $data['num_prefix_1'],
                        'pref2' => $data['num_prefix_2'],
                        'no' => $data['internal_number']))); break;
    case "org": return $app->redirect(
                    $app['url_generator']->generate('clerk.doc.view', array('year' => date("Y"),
                        'pref1' => $data['num_prefix_1'],
                        //'pref2' => $data['num_prefix_1'],
                        'no' => $data['internal_number']))); break;
    default: return $app->redirect($app['url_generator']->generate('clerk.start')); break;
}
    
})->bind('clerk.doc.push');

$app->get('/clerk/view/id:{doc}', function($doc) use ($app) {
    $data = array();
    $data['doc'] = RDAStatic::getDocById($doc);
    return $app['twig']->render('clerk.skarga.twig', $data);
})->bind('clerk.doc.view');

$app->get('/clerk/edit/id:{doc}', function($doc) use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['doc'] = RDAStatic::getDocById($doc);
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['new_num'] = RDAStatic::getMaxNumPeopleDoc() + 1;
    return $app['twig']->render('clerk.newdoc.people.twig', $data);
})->bind('clerk.doc.edit');


/**
 $app->get('/clerk/view/no:{pref1}-{no}/{year}', function($year, $pref1, $no) use ($app) {
    $data = array();
    $data['doc'] = RDAStatic::getDocBy($pref1, $pref1, $no, $year);
    return $app['twig']->render('clerk.skarga.twig', $data);
})->bind('clerk.doc.type1.view');

$app->get('/clerk/view/no:{pref1}-{pref2}-{no}/{year}', function($year, $pref1, $pref2, $no) use ($app) {
    $data = array();
    $data['doc'] = RDAStatic::getDocBy($pref1, $pref1, $no, $year);
    return $app['twig']->render('clerk.skarga.twig', $data);
})->bind('clerk.doc.type2.view');

$app->get('/clerk/edit/no:{pref1}-{no}/{year}', function($year, $pref1, $no) use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['doc'] = RDAStatic::getDocBy($pref1, $pref1, $no, $year);
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['new_num'] = RDAStatic::getMaxNumPeopleDoc() + 1;
    return $app['twig']->render('clerk.newdoc.people.twig', $data);
})->bind('clerk.doc.type1.edit');


 * 
 * 
 */
