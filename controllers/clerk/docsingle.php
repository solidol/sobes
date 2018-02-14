<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$app->get('/clerk/view/id:{doc}', function($doc) use ($app) {
    $data = array();
    $data['userlist'] = RDAStatic::getManagers();
    $data['userlist'] = RDAStatic::getByRoles(array('ROLE_DEPT','ROLE_MANAGER'));
    $data['doc'] = RDAStatic::getDocById($doc);
    $data['doc']['externals'] = RDAStatic::getExternalsByDocId($doc);
    $data['doc']['notes'] = RDAStatic::getNotesByDocId($doc);
    $data['doc']['donestr'] = RDAStatic::getNotesByDocId($doc, 'donestr');
    $data['doc']['resolution'] = RDAStatic::getResolutionByDocId($doc);
    $data['doc']['movings'] = RDAStatic::getMovingsByDocId($doc);
    
    foreach ($data['doc']['externals'] as &$item){
        $item['serial']=unserialize($item['value']);
    }
    unset($item);
    
    foreach ($data['doc']['donestr'] as &$item){
        $item['serial']=unserialize($item['value']);
    }
    unset($item);
    
    foreach ($data['doc']['notes'] as &$item){
        $item['serial']=unserialize($item['value']);
    }
    unset($item);
    
    foreach ($data['doc']['resolution'] as &$item){
        $item['serial']=unserialize($item['value']);
    }
    unset($item);
    
    foreach ($data['doc']['movings'] as &$item){
        $item['serial']=unserialize($item['value']);
    }
    unset($item);
    
    
    $data['doc']['others'] = RDAStatic::getOthersTsByDocId($doc);
    
    if (!empty($data['doc']['peoples'])) {
        //var_dump($data['doc']['peoples']);
        foreach ($data['doc']['peoples'] as $item) {
            $data['doc']['socials'][] = RDAStaticPeople::getSocialStatusesByPeopleId($item['people_id']);
        }
    } else
        $data['doc']['socials'] = RDAStaticPeople::getSocialStatusesByPeopleId($data['doc']['pid']);
    //var_dump($data['doc']['socials']);
    switch ($data['doc']['type']) {
        case "people":
            if ($data['doc']['num_prefix_1'] == "КЗ")
                return $app['twig']->render('clerk.doc.collect.twig', $data);
            else
                return $app['twig']->render('clerk.doc.people.twig', $data);
            break;
        case "state": return $app['twig']->render('clerk.doc.state.twig', $data);
            break;
        case "org": return $app['twig']->render('clerk.doc.org.twig', $data);
            break;
        case "visitors": 
            return $app['twig']->render('clerk.doc.visitors.twig', $data);
            break;
        default: return $app->redirect($app['url_generator']->generate('clerk.start'));
            break;
    }
    return $app->redirect($app['url_generator']->generate('clerk.start'));
})->bind('clerk.doc.view');

$app->get('/clerk/toarch/id:{doc}', function($doc) use ($app) {
    RDAStatic::moveToArchById($doc);
    return $app->redirect($app['url_generator']->generate('clerk.arch.view', array('doc' => $doc)));
})->bind('clerk.doc.movetoarch');

$app->get('/clerk/edit/id:{doc}', function($doc) use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['doc'] = RDAStatic::getDocById($doc);
    $data['doc']['notes'] = RDAStatic::getNotesByDocId($doc);
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    //$data['fullnum'] = '';
    $dt_in = explode("-", $data['doc']['date_in']);
    $data['doc']['date_in'] = $dt_in[2] . '.' . $dt_in[1] . '.' . $dt_in[0];
    $dt_cr = explode("-", $data['doc']['date_control']);
    $data['doc']['date_control'] = $dt_cr[2] . '.' . $dt_cr[1] . '.' . $dt_cr[0];

    switch ($data['doc']['type']) {
        case "people": return $app['twig']->render('clerk.edit.people.twig', $data);
            break;
        case "state": return $app['twig']->render('clerk.edit.state.twig', $data);
            break;
        case "org": return $app['twig']->render('clerk.edit.org.twig', $data);
            break;
        case "visitors": return $app['twig']->render('clerk.edit.visitors.twig', $data);
            break;
        default: return $app->redirect($app['url_generator']->generate('clerk.start'));
            break;
    }

    return $app['twig']->render('clerk.newdoc.people.twig', $data);
})->bind('clerk.doc.edit');

$app->get('/clerk/archview/id:{doc}', function($doc) use ($app) {
    $data = array();
    $data['userlist'] = RDAStatic::getManagers();
    $data['doc'] = RDAStatic::getArchDocById($doc);
    $data['doc']['externals'] = RDAStatic::getExternalsByDocId($doc);
    $data['doc']['notes'] = RDAStatic::getNotesByDocId($doc);
    $data['doc']['donestr'] = RDAStatic::getNotesByDocId($doc, 'donestr');
    //var_dump($data['doc']['donestr']);
    $data['doc']['serialpost'] = RDAStatic::getNotesByDocId($doc, 'serialpost');
    $data['doc']['resolution'] = RDAStatic::getResolutionByDocId($doc);
    $data['doc']['movings'] = RDAStatic::getMovingsByDocId($doc);
    $data['doc']['others'] = RDAStatic::getOthersTsByDocId($doc);

    if (!empty($data['doc']['peoples'])) {
        //var_dump($data['doc']['peoples']);
        foreach ($data['doc']['peoples'] as $item) {
            $data['doc']['socials'][] = RDAStaticPeople::getSocialStatusesByPeopleId($item['people_id']);
        }
    } else
        $data['doc']['socials'] = RDAStaticPeople::getSocialStatusesByPeopleId($data['doc']['pid']);
    //var_dump($data['doc']['socials']);
    switch ($data['doc']['type']) {
        case "people":
            if ($data['doc']['num_prefix_1'] == "КЗ")
                return $app['twig']->render('clerk.doc.collect.twig', $data);
            else
                return $app['twig']->render('clerk.doc.people.twig', $data);
            break;
        case "state": return $app['twig']->render('clerk.doc.state.twig', $data);
            break;
        case "org": return $app['twig']->render('clerk.doc.org.twig', $data);
            break;
        case "visitors": 
            $data['userlist'] = RDAStatic::getByRoles(array('ROLE_DEPT','ROLE_MANAGER'));
            return $app['twig']->render('clerk.doc.visitors.twig', $data);
            break;
        default: return $app->redirect($app['url_generator']->generate('clerk.start'));
            break;
    }
    return $app->redirect($app['url_generator']->generate('clerk.start'));
})->bind('clerk.arch.view');

