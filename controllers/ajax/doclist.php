<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Symfony\Component\HttpFoundation\JsonResponse;

$app->get('/ajax/document/getlist', function() use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $columnsParam = $get->get('columns') ? $get->get('columns') : array();
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    if ($type)
        $keys['type'] = $searchstring; // ??????? WTF ?????????
    if (!empty($columnsParam))
        foreach ($columnsParam as $key => $value) {
            switch ($key) {
                case 0: if ($value['search']['value'] > '')
                        $keys['fullnum'] = $value['search']['value'];
                    break;
                case 1: if ($value['search']['value'] > '')
                        $keys['date_in'] = $value['search']['value'];
                    break;
                case 2: if ($value['search']['value'] > '')
                        $keys['date_control'] = $value['search']['value'];
                    break;
            }
        }
    $result = RDAStatic::getDocList($keys, "AND", $limit, $search);
    $arDocs['data'] = array();
    foreach ($result as $k => $v) {

        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = $v['fullnum'];
        $item['notes'] = '';
        if ($v['impstatus'] == 'ugl')
            $item['notes'] .= ' <span style="background-color:red;color:white;font-size:20px">УГЛ</span>';
        if ($v['donestatus'] == 'p')
            $item['notes'] .= ' <span style="color:rgb(0, 150, 60);font-size:20px">+</span>';
        if ($v['donestatus'] == 'm')
            $item['notes'] .= ' <span style="color:red;font-size:20px">-</span>';
        if ($v['donestatus'] == 'r')
            $item['notes'] .= ' <span style="color:blue;font-size:20px">P</span>';
        $item['num'].=$item['notes'];
        $item['date_in'] = $v['date_in'];
        $item['date_control'] = $v['date_control'];
        $item['timetolife'] = $v['timetolife'];
        $item['comment'] = $v['comment'];
        $item['summary'] = $v['summary'];
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id']))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = '<a href="#" onClick="moveToArch(' . $v['id'] . ')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>';
        $item['donestatus'] = $v['donestatus'];
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
    $datecontrol = urldecode($get->get('datecontrol'));
    $columnsParam = $get->get('columns') ? $get->get('columns') : array();
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys['fullnum'] = $get->get('columns')[0]['search']['value'];
    $keys['date_in_text'] = str_replace(',','.',$get->get('columns')[1]['search']['value']);
    $keys['date_control_text'] = str_replace(',','.',$get->get('columns')[2]['search']['value']);
    $keys['fullname'] = $get->get('columns')[3]['search']['value'];
    
    if ($type)
        $keys['type'] = $type;
    if ($datecontrol)
        $keys['date_control'] = $datecontrol;
    if (!empty($columnsParam))
        foreach ($columnsParam as $key => $value) {

            switch ($key) {
                case 0: if ($value['search']['value'] != '')
                        $keys['fullnum'] = $value['search']['value'];
                    break;
                case 1: if ($value['search']['value'] != '')
                        $keys['date_in'] = $value['search']['value'];
                    break;
                case 2: if ($value['search']['value'] != '')
                        $keys['date_control'] = $value['search']['value'];
                    break;
            }
        }
    //var_dump($keys);
    $result = RDAStatic::getDocList($keys, "AND", $limit, $search);
    $arDocs['data'] = array();
    foreach ($result as $k => $v) {

        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = ' <div style="background-color:white;color:black;font-size:20px">'.$v['fullnum'].'</div>';;
        $item['notes'] = '';
        if ($v['impstatus'] == 'ugl')
            $item['notes'] .= ' <div style="background-color:red;color:white;font-size:14px">УГЛ</div>';
        if (isset($v['num_types'])) 
            $item['notes'] .= ' <div style="background-color:brown;color:white;font-size:14px">'.$v['num_types'].'</div>';
        
        if ($v['donestatus'] == 'p')
            $item['notes'] .= ' <div style="color:white;background-color:rgb(0, 150, 60);font-size:14px">+</div>';
        if ($v['donestatus'] == 'm')
            $item['notes'] .= ' <div style="color:white;background-color:red;font-size:14px">-</div>';
        if ($v['donestatus'] == 'r')
            $item['notes'] .= ' <div style="color:white;background-color:blue;font-size:14px">P</div>';
        $item['num'].=$item['notes'];
        $item['date_in'] = $v['date_in_text'];
        $item['date_control'] = $v['date_control_text'];
        $item['timetolife'] = $v['timetolife'];
        $item['comment'] = $v['comment'];
        $item['summary'] = $v['summary'];
        if ($type=="people" or $type=="visitors"){
            $item['fullname'] = $v['fullname'];
        }
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id']))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = ($v['donestatus']!='')?'<a href="#" onClick="moveToArch(' . $v['id'] . ')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>':'';
        $item['del'] = '<a href="#" onClick="docDelete(' . $v['id'] . ')"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>';
        $item['donestatus'] = $v['donestatus'];
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
    $arDocs['data'] = array();
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
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id'])) .
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
    $datecontrol = urldecode($get->get('datecontrol'));
    $columnsParam = $get->get('columns') ? $get->get('columns') : array();
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys = array();
    $keys['fullnum'] = $get->get('columns')[0]['search']['value'];
    $keys['date_in_text'] = str_replace(',','.',$get->get('columns')[1]['search']['value']);
    $keys['date_control_text'] = str_replace(',','.',$get->get('columns')[2]['search']['value']);
    $keys['fullname'] = $get->get('columns')[3]['search']['value'];
    if ($type)
        $keys['type'] = $type;
    if ($datecontrol)
        $keys['date_control'] = $datecontrol;
    if (!empty($columnsParam))
        foreach ($columnsParam as $key => $value) {

            switch ($key) {
                case 0: if ($value['search']['value'] != '')
                        $keys['fullnum'] = $value['search']['value'];
                    break;
                case 1: if ($value['search']['value'] != '')
                        $keys['date_in'] = $value['search']['value'];
                    break;
                case 2: if ($value['search']['value'] != '')
                        $keys['date_control'] = $value['search']['value'];
                    break;
            }
        }
    //var_dump($keys);
    $result = RDAStatic::getDocList($keys, "AND", $limit, $search, 1);
    $arDocs['data'] = array();
    foreach ($result as $k => $v) {

        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = ' <div style="background-color:white;color:black;font-size:20px">'.$v['fullnum'].'</div>';;
        $item['notes'] = '';
        if ($v['impstatus'] == 'ugl')
            $item['num'] .= ' <div style="background-color:red;color:white;font-size:14px">УГЛ</div>';
        if ($v['num_types'] != null)
            $item['num'] .= ' <div style="background-color:brown;color:white;font-size:14px">'.$v['num_types'].'</div>';
        
        if ($v['donestatus'] == 'p')
            $item['num'] .= ' <div style="color:white;background-color:rgb(0, 150, 60);font-size:14px">+</div>';
        if ($v['donestatus'] == 'm')
            $item['num'] .= ' <div style="color:white;background-color:red;font-size:14px">-</div>';
        if ($v['donestatus'] == 'r')
            $item['num'] .= ' <div style="color:white;background-color:grey;font-size:14px">P</div>';
        $item['date_in'] = $v['date_in_text'];
        $item['date_control'] = $v['date_control_text'];
        $item['timetolife'] = $v['timetolife'];
        $item['comment'] = $v['comment'];
        $item['summary'] = $v['summary'];
        if ($type=="people" or $type=="visit"){
            $item['fullname'] = $v['fullname'];
        }
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id']))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';
        $item['donestatus'] = $v['donestatus'];
        $item['del'] = '<a href="#" onClick="docDelete(' . $v['id'] . ')"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>';
        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount();
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search);
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlist.archivetyped');

$app->get('/ajax/report/org/monthcontrol:{startDate}:{endDate}', function($startDate, $endDate) use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];

    $result = RDAStatic::getOrgMonthReport($startDate, $endDate);
    $arDocs['data'] = array();
    foreach ($result as $k => $v) {

        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = ' <div style="background-color:white;color:black;font-size:20px">'.$v['fullnum'].'</div>';;
        $item['notes'] = '';
        if ($v['impstatus'] == 'ugl')
            $item['num'] .= ' <div style="background-color:red;color:white;font-size:14px">УГЛ</div>';
        if ($v['num_types'] != null)
            $item['num'] .= ' <div style="background-color:brown;color:white;font-size:14px">'.$v['num_types'].'</div>';
        
        if ($v['donestatus'] == 'p')
            $item['num'] .= ' <div style="color:white;background-color:rgb(0, 150, 60);font-size:14px">+</div>';
        if ($v['donestatus'] == 'm')
            $item['num'] .= ' <div style="color:white;background-color:red;font-size:14px">-</div>';
        if ($v['donestatus'] == 'r')
            $item['num'] .= ' <div style="color:white;background-color:blue;font-size:14px">P</div>';
        $item['date_in'] = $v['date_in_text'];
        $item['date_control'] = $v['date_control_text'];
        $item['timetolife'] = $v['timetolife'];
        $item['comment'] = $v['comment'];
        $item['summary'] = $v['summary'];

        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id']))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = ($v['donestatus']!='')?'<a href="#" onClick="moveToArch(' . $v['id'] . ')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>':'';
        $item['donestatus'] = $v['donestatus'];
        $arDocs['data'][] = $item;
    }

    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.report.org.monthcontrol');

$app->get('/ajax/document/donestrings/getlist:{type}', function($type) use ($app) {
    $result = array();
    $arDocs = array();
    $get = $app['request'];
    $type = urldecode($get->get('type'));
    $datecontrol = urldecode($get->get('datecontrol'));
    $columnsParam = $get->get('columns') ? $get->get('columns') : array();
    $limit['draw'] = $get->get('draw') ? $get->get('draw') : 1;
    $limit['length'] = $get->get('length') ? $get->get('length') : 10;
    $limit['start'] = $get->get('start') ? $get->get('start') : 0;
    $search = $get->get('search') ? $get->get('search')['value'] : false;
    $keys['fullnum'] = $get->get('columns')[0]['search']['value'];
    $keys['date_in_text'] = str_replace(',','.',$get->get('columns')[1]['search']['value']);
    $keys['date_control_text'] = str_replace(',','.',$get->get('columns')[2]['search']['value']);
    $keys['fullname'] = $get->get('columns')[3]['search']['value'];
    
    if ($type)
        $keys['type'] = $type;
    if ($datecontrol)
        $keys['date_control'] = $datecontrol;
    if (!empty($columnsParam))
        foreach ($columnsParam as $key => $value) {

            switch ($key) {
                case 0: if ($value['search']['value'] != '')
                        $keys['fullnum'] = $value['search']['value'];
                    break;
                case 1: if ($value['search']['value'] != '')
                        $keys['date_in'] = $value['search']['value'];
                    break;
                case 2: if ($value['search']['value'] != '')
                        $keys['date_control'] = $value['search']['value'];
                    break;
            }
        }
    //var_dump($keys);
    $result = RDAStatic::getDocList($keys, "AND", $limit, $search, false, true);
    $arDocs['data'] = array();
    foreach ($result as $k => $v) {

        $item['num'] = '';
        if ($v['num_prefix_1'] != '')
            $item['num'] .= $v['num_prefix_1'];
        if ($v['num_prefix_2'] != '')
            $item['num'] .= '-' . $v['num_prefix_2'];
        if ($v['internal_number'] != '')
            $item['num'] .= '-' . $v['internal_number'];
        $item['num'] = ' <div style="background-color:white;color:black;font-size:20px">'.$v['fullnum'].'</div>';;
        $item['notes'] = '';
        if ($v['impstatus'] == 'ugl')
            $item['num'] .= ' <div style="background-color:red;color:white;font-size:14px">УГЛ</div>';
        if ($v['num_types'] != null)
            $item['num'] .= ' <div style="background-color:brown;color:white;font-size:14px">'.$v['num_types'].'</div>';
        
        if ($v['donestatus'] == 'p')
            $item['num'] .= ' <div style="color:white;background-color:rgb(0, 150, 60);font-size:14px">+</div>';
        if ($v['donestatus'] == 'm')
            $item['num'] .= ' <div style="color:white;background-color:red;font-size:14px">-</div>';
        if ($v['donestatus'] == 'r')
            $item['num'] .= ' <div style="color:white;background-color:blue;font-size:14px">P</div>';
        $item['date_in'] = $v['date_in_text'];
        $item['date_control'] = $v['date_control_text'];
        $item['timetolife'] = $v['timetolife'];
        $item['donestr'] = $v['donestr'];
        $item['summary'] = $v['summary'];
        if ($type=="people" or $type=="visitors"){
            $item['fullname'] = $v['fullname'];
        }
        $item['view'] = '<a href="' . $app['url_generator']->generate('clerk.doc.view', array('doc' => $v['id']))
                . '"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i></a>';
        $item['arch'] = ($v['donestatus']!='')?'<a href="#" onClick="moveToArch(' . $v['id'] . ')"><i class="fa fa-archive fa-2x" aria-hidden="true"></i></a>':'';
        $item['del'] = '<a href="#" onClick="docDelete(' . $v['id'] . ')"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>';
        $item['donestatus'] = $v['donestatus'];
        $arDocs['data'][] = $item;
    }
    $arDocs['draw'] = $get->get('draw') ? $get->get('draw') : false;
    $arDocs['recordsTotal'] = RDAStatic::getDocCount();
    $arDocs['recordsFiltered'] = RDAStatic::getDocCount($keys, "AND", $search);
    $resp = new JsonResponse($arDocs);
    return $resp->setCallback(
                    $get->get('callback')
    );
})->bind('ajax.document.getlisttyped.donestrings');

