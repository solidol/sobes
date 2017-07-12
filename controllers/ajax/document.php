<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Symfony\Component\HttpFoundation\JsonResponse;

$app->get('/ajax/document/shortsearch', function() use ($app) {
    $result = array();
    $arPeople = array();
    $get = $app['request'];
    $searchstring = urldecode($get->get('term'));
    $keys['firstname'] = $searchstring;
    $keys['secondname'] = $searchstring;
    $keys['lastname'] = $searchstring;
    $keys['passport'] = $searchstring;
    $result = RDAStatic::getDocList($keys, "OR");
    foreach ($result as $k => $v) {
        $item['id'] = $v['id'];
        $item['label'] = $v['lastname'] . ' ' . $v['firstname'] . ' ' . $v['passport'];
        $item['value'] = $v['lastname'] . ' ' . $v['firstname'];
        $arPeople[] = $item;
    }
    $resp = new JsonResponse($arPeople);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.shortsearch');

$app->get('/ajax/document/getlist', function() use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    if ($type)
        $keys['type'] = $searchstring; // ??????? WTF ?????????

    $result = RDAStatic::getDocList($keys, "AND", $limit, $search);
    $arDocs['data']=array();
    foreach ($result as $k => $v) {
        
        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = $v['fullnum'];
        $item['date_in'] = $v['date_in'];
        $item['date_control'] = $v['date_control'];
        $item['timetolife'] = $v['timetolife'];
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id'] ))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';

        $item['edit'] = '<a href="' . $app['url_generator']->generate('clerk.doc.edit', array('doc' => $v['id']))
                . '"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>';

        $item['arch'] = '<a href="' . $app['url_generator']->generate('clerk.doc.movetoarch', array('doc' => $v['id']))
                . '" onClick="moveToArch('.$v['id'].')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = '<a href="#" onClick="moveToArch('.$v['id'].')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>';
        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount();
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search);
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlist');

$app->get('/ajax/document/getlist:{type}', function($type) use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    if ($type)
        $keys['type'] = $type; // ??????? WTF ?????????

    $result = RDAStatic::getDocList($keys, "AND", $limit, $search);
    $arDocs['data']=array();
    foreach ($result as $k => $v) {
        
        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = $v['fullnum'];
        $item['date_in'] = $v['date_in'];
        $item['date_control'] = $v['date_control'];
        $item['timetolife'] = $v['timetolife'];
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id'] ))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';

        $item['edit'] = '<a href="' . $app['url_generator']->generate('clerk.doc.edit', array('doc' => $v['id']))
                . '"><i class="fa fa-pencil-square-o fa-2x" aria-hidden="true"></i></a>';

        $item['arch'] = '<a href="' . $app['url_generator']->generate('clerk.doc.movetoarch', array('doc' => $v['id']))
                . '" onClick="moveToArch('.$v['id'].')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = '<a href="#" onClick="moveToArch('.$v['id'].')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>';
        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount();
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search);
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlisttyped');

$app->get('/ajax/document/archivelist', function() use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    if ($type)
        $keys['type'] = $searchstring; // ??????? WTF ?????????

    $result = RDAStatic::getDocList($keys, "AND", $limit, $search, "archive");
    $arDocs['data']=array();
    foreach ($result as $k => $v) {
        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['date_in'] = $v['date_in'];
        $item['date_control'] = $v['date_control'];
        $item['timetolife'] = $v['timetolife'];
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id'] )). 
                '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';

        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount(false, "AND", false, "archive");
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search, "archive");
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlist.archive');

$app->get('/ajax/document/archivelist:{type}', function($type) use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    if ($type)
        $keys['type'] = $type;

    $result = RDAStatic::getDocList($keys, "AND", $limit, $search, "archive");
    $arDocs['data']=array();
    foreach ($result as $k => $v) {
        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['date_in'] = $v['date_in'];
        $item['date_control'] = $v['date_control'];
        $item['timetolife'] = $v['timetolife'];
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id'] )). 
                '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';

        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount(false, "AND", false, "archive");
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search, "archive");
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlist.archivetyped');

