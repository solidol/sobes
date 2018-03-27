<?php

$app->get('/clerk/newdoc/people', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['peopleattr'] = RDAStaticPeople::getSocialStatuses();
    $data['bosslist'] = RDAStatic::getManagers(true);
    $data['new_num'] = RDAStatic::getMaxNumDoc("people") + 1;
    return $app['twig']->render('clerk.newdoc.people.twig', $data);
})->bind('clerk.doc.new.people');

$app->get('/clerk/newdoc/collect', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['peopleattr'] = RDAStaticPeople::getSocialStatuses();
    $data['bosslist'] = RDAStatic::getManagers(true);
    $data['new_num'] = RDAStatic::getMaxNumDoc("people") + 1;
    return $app['twig']->render('clerk.newdoc.collect.twig', $data);
})->bind('clerk.doc.new.collect');

$app->get('/clerk/newdoc/visitors', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['peopleattr'] = RDAStaticPeople::getSocialStatuses();
    $data['bosslist'] = RDAStatic::getManagers(true);
    $data['new_num'] = RDAStatic::getMaxNumDoc("visitors") + 1;
    return $app['twig']->render('clerk.newdoc.visitors.twig', $data);
})->bind('clerk.doc.new.visitors');

$app->get('/clerk/newdoc/org', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['new_num'] = "";
    $data['bosslist'] = RDAStatic::getManagers(true);
    $data['doctypes'] = RDAStatic::getOrgDocTypes();
    $data['doctcontent'] = RDAStatic::getContentDocTypes();
    return $app['twig']->render('clerk.newdoc.org.twig', $data);
})->bind('clerk.doc.new.org');

$app->get('/clerk/newdoc/state', function() use ($app) {
    $data = array();
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data['username'] = $user->getName();
    $data['userid'] = $user->getId();
    $data['bosslist'] = RDAStatic::getManagers(true);
    $data['new_num'] = RDAStatic::getMaxNumDoc("state") + 1;
    $data['doctypes'] = RDAStatic::getStateDocTypes();
    return $app['twig']->render('clerk.newdoc.state.twig', $data);
})->bind('clerk.doc.new.state');



$app->post('/clerk/newdoc/people', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $dt_in = explode(".", $post->get('date_in'));
    $dt_in = $dt_in[2] . '-' . $dt_in[1] . '-' . $dt_in[0];
    $dt_cr = explode(".", $post->get('date_control'));
    $dt_cr = $dt_cr[2] . '-' . $dt_cr[1] . '-' . $dt_cr[0];

    $data['num_prefix_0'] = ($post->get('num_prefix_0') != null) ? $post->get('num_prefix_0') : '';
    $data['num_prefix_1'] = ($post->get('num_prefix_1') != null) ? $post->get('num_prefix_1') : '';
    $data['num_prefix_2'] = ($post->get('num_prefix_2') != null) ? $post->get('num_prefix_2') : '';
    $data['internal_number'] = $post->get('internal_number');

    $orgnums = $post->get('externalnums');
    $orgs = $post->get('externalorgs');
    $dates = $post->get('externaldates');
    $externals = array();
    foreach ($orgnums as $k => $v) {
        $newitem['number'] = $v;
        $newitem['org'] = $orgs[$k];
        $newitem['date'] = $dates[$k];
        $externals[] = $newitem;
    }

    //$data['external_number'] = $post->get('external_number');
    $data['year'] = (int) date("Y");
    $data['date_create'] = date("Y-m-d");
    $data['date_in'] = $dt_in;
    $data['date_control'] = $dt_cr;
    $data['type'] = $post->get('doctype');
    $data['topicstarter'] = $post->get('topicstarter') ? $post->get('topicstarter') : 0;
    $data['topicstarter_org'] = 0;
    //$post->get('orgstarter') ? $post->get('orgstarter') : 0;
    $data['created_by'] = $user->getId();
    $data['curr_user'] = $user->getId();
    $data['summary'] = $post->get('summary');
    $data['comment'] = $post->get('comment');

    $data['impstatus'] = $post->get('impstatus') ? 'ugl' : '';
    $data['topicstarter_text'] = $post->get('tstext');
    if ($data['type'] == "people") {
        $peoples = explode(",", $data['topicstarter']);
        $data['topicstarter'] = $peoples[0];
    }

    $app['db']->insert('document', $data);
    $newId = $app['db']->lastInsertId();

    RDAStaticPeople::insertPeoplesDoc($newId, $peoples);
    $boss = RDAStatic::getBoss();
    if ($post->get('doctype') != "visitors")
        RDAStatic::moveDoc($newId, $user->getId(), $boss['id'], 'Створено картку');
    else
        RDAStatic::moveDoc($newId, $user->getId(), $post->get('selectinterviewer'), 'Створено картку');
    RDAStatic::pushExternalsByDocId($newId, $externals);
    

    return $app->redirect($app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
})->bind('clerk.doc.push.collect');



$app->post('/clerk/newdoc', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $data['isreapeted'] = $post->get('isreapeted') ? $post->get('isreapeted') : "no";
    
    $dt_in = explode(".", $post->get('date_in'));
    $dt_in = $dt_in[2] . '-' . $dt_in[1] . '-' . $dt_in[0];
    $dt_cr = explode(".", ($post->get('date_control')>'')?$post->get('date_control'):'00.00.0000');
    $dt_cr = $dt_cr[2] . '-' . $dt_cr[1] . '-' . $dt_cr[0];

    $data['num_prefix_0'] = ($post->get('num_prefix_0') != null) ? $post->get('num_prefix_0') : '';
    $data['num_prefix_1'] = ($post->get('num_prefix_1') != null) ? $post->get('num_prefix_1') : '';
    $data['num_prefix_2'] = ($post->get('num_prefix_2') != null) ? $post->get('num_prefix_2') : '';
    $data['internal_number'] = $post->get('internal_number');

    $orgnums = $post->get('externalnums');
    $orgs = $post->get('externalorgs');
    $dates = $post->get('externaldates');
    $externals = array();
    //var_dump($orgnums);
    //die('');
    if (!empty($orgnums[0]))
    foreach ($orgnums as $k => $v) {
        $newitem['number'] = $v;
        $newitem['org'] = $orgs[$k];
        $newitem['date'] = $dates[$k];
        $externals[] = $newitem;
    }
    $data['impstatus'] = $post->get('impstatus') ? 'ugl' : '';
    $data['external_number'] = $post->get('external_number');
    $data['year'] = (int) date("Y");
    $data['date_create'] = date("Y-m-d");
    $data['date_in'] = $dt_in;
    $data['date_control'] = $dt_cr;
    $data['type'] = $post->get('doctype');
    $data['topicstarter'] = $post->get('topicstarter') ? $post->get('topicstarter') : 0;
    $data['topicstarter_org'] = $post->get('orgstarter') ? $post->get('orgstarter') : 0;
    $data['created_by'] = $user->getId();
    $data['curr_user'] = $user->getId();
    $data['summary'] = $post->get('summary');
    $data['comment'] = $post->get('comment');
    
    $data['topicstarter_text'] = $post->get('tstext');
    if ($data['type'] == "people") {
        $peoples = explode(",", $data['topicstarter']);
        $data['topicstarter'] = $peoples[0];
    }
    $app['db']->insert('document', $data);
    $newId = $app['db']->lastInsertId();
    if ($data['type'] == "people")
        RDAStaticPeople::insertPeoplesDoc($newId, $peoples);
    $boss = RDAStatic::getBoss();
    if (($post->get('doctype') != "visitors") or ($post->get('doctype') != "people"))
    //    RDAStatic::moveDoc($newId, $user->getId(), $boss['id'], 'Створено картку');
    //else
        RDAStatic::moveDoc($newId, $user->getId(), $post->get('selectinterviewer'), 'Створено картку, передано на резолюцію');
    RDAStatic::pushExternalsByDocId($newId, $externals);
    switch ($post->get('doctype')) {
        case "people": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "state": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "org": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "visitors": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        default: return $app->redirect($app['url_generator']->generate('clerk.start'));
            break;
    }
})->bind('clerk.doc.push');

$app->post('/clerk/upddoc', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];

    $dt_in = explode(".", $post->get('date_in'));
    $dt_in = $dt_in[2] . '-' . $dt_in[1] . '-' . $dt_in[0];
    $dt_cr = explode(".", $post->get('date_control'));
    $dt_cr = $dt_cr[2] . '-' . $dt_cr[1] . '-' . $dt_cr[0];
    $id = $post->get('docid');
    $data['date_in'] = $dt_in;
    $data['date_control'] = $dt_cr;
    $data['summary'] = $post->get('summary');
    $data['comment'] = $post->get('comment');
    $app['db']->update('document', $data, array('id' => $id));
    RDAStatic::pushNotesByDocId($id, $post->get('notes'));
    $newId = $id;

    switch ($post->get('doctype')) {
        case "people": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "state": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "org": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        case "visitors": return $app->redirect(
                            $app['url_generator']->generate('clerk.doc.view', array('doc' => $newId)));
            break;
        default: return $app->redirect($app['url_generator']->generate('clerk.start'));
            break;
    }
})->bind('clerk.doc.update');

