<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->post('/clerk/report/total', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];
    $dt1= explode('.', $post->get('date_start'));
    $dt2=explode('.', $post->get('date_end'));
    $arDates = array('startDate' => $dt1[2].'-'.$dt1[1].'-'.$dt1[0], 'endDate' => $dt2[2].'-'.$dt2[1].'-'.$dt2[0]);
    return $app->redirect($app['url_generator']->generate('clerk.report.total', $arDates));
})->bind('clerk.report.totalpost');

$app->post('/clerk/report/totalmonth', function() use ($app) {
    $token = $app['security']->getToken();
    $user = $token->getUser();
    $data = array();
    $post = $app['request'];
    $yr= $post->get('year');
    if (!($yr>2000)and($yr<2050)) $yr=date('Y');
    $arDates = array('startY' => $yr);
    return $app->redirect($app['url_generator']->generate('clerk.report.totalmonth', $arDates));
})->bind('clerk.report.totalpostmonth');

$app->get('/clerk/report/total:{startDate}:{endDate}', function($startDate, $endDate) use ($app) {

    $data = array();
    $totalTmp = RDAStaticReport::getTotalByInterval($startDate, $endDate);
    $maleTmp = RDAStaticReport::getTotalByInterval($startDate, $endDate, false, array("sex = 'm'"));
    $femaleTmp = RDAStaticReport::getTotalByInterval($startDate, $endDate, false, array("sex = 'f'"));
    $uglTmp = RDAStaticReport::getTotalByInterval($startDate, $endDate, false, array("impstatus LIKE '%ugl%'"));
    $odaTmp = RDAStaticReport::getTotalByIntervalAndExternals($startDate, $endDate, false, false, 'ОДА');
    $oblradaTmp = RDAStaticReport::getTotalByIntervalAndExternals($startDate, $endDate, false, false, 'Облрада');
    $mvkTmp = RDAStaticReport::getTotalByIntervalAndExternals($startDate, $endDate, false, false, 'МВК');
    $rvkTmp = RDAStaticReport::getTotalByIntervalInternal($startDate, $endDate, false, false);

    $arReportFormatted = array();
    $arReportFormatted['header']['term'] = '';
    $arReportFormatted['total']['term'] = 'Всього';
    $arReportFormatted['male']['term'] = 'Чоловіків';
    $arReportFormatted['female']['term'] = 'Жінок';
    $arReportFormatted['ugl']['term'] = 'УГЛ';
    $arReportFormatted['oda']['term'] = 'ОДА';
    $arReportFormatted['oblrada']['term'] = 'Облрада';
    $arReportFormatted['mvk']['term'] = 'МВК';
    $arReportFormatted['rvk']['term'] = 'РВК';
    $data['dates'] = array();
    $sum['total'] = 0;
    $sum['male'] = 0;
    $sum['female'] = 0;
    $sum['ugl'] = 0;
    $sum['oda'] = 0;
    $sum['oblrada'] = 0;
    $sum['mvk'] = 0;
    $sum['rvk'] = 0;
    $sum['t'] = 0;
    $arReportFormatted['header']['data'] = array();
    $arReportFormatted['total']['data'] = array();
    $arReportFormatted['male']['data'] = array();
    $arReportFormatted['female']['data'] = array();
    foreach ($totalTmp as $key => $dItem) {

        $arReportFormatted['header']['data'][] = $dItem['theDate'];
        $data['dates'][] = $dItem['theDate'];
        $arReportFormatted['total']['data'][] = $dItem['num'];
        $arReportFormatted['male']['data'][] = $maleTmp[$key]['num'];
        $arReportFormatted['female']['data'][] = $femaleTmp[$key]['num'];
        $arReportFormatted['ugl']['data'][] = $uglTmp[$key]['num'];
        $arReportFormatted['oda']['data'][] = $odaTmp[$key]['num'];
        $arReportFormatted['oblrada']['data'][] = $oblradaTmp[$key]['num'];
        $arReportFormatted['mvk']['data'][] = $mvkTmp[$key]['num'];
        $arReportFormatted['rvk']['data'][] = $rvkTmp[$key]['num'];
        $sum['total'] += $dItem['num'];
        $sum['male'] += $maleTmp[$key]['num'];
        $sum['female'] += $femaleTmp[$key]['num'];
        $sum['ugl'] += $uglTmp[$key]['num'];
        $sum['oda'] += $odaTmp[$key]['num'];
        $sum['oblrada'] += $oblradaTmp[$key]['num'];
        $sum['mvk'] += $mvkTmp[$key]['num'];
        $sum['rvk'] += $rvkTmp[$key]['num'];
    }
    $arReportFormatted['total']['total'] = $sum['total'];
    $arReportFormatted['male']['total'] = $sum['male'];
    $arReportFormatted['female']['total'] = $sum['female'];
    $arReportFormatted['ugl']['total'] = $sum['ugl'];
    $arReportFormatted['oda']['total'] = $sum['oda'];
    $arReportFormatted['oblrada']['total'] = $sum['oblrada'];
    $arReportFormatted['mvk']['total'] = $sum['mvk'];
    $arReportFormatted['rvk']['total'] = $sum['rvk'];
    $data['report'] = $arReportFormatted;

    return $app['twig']->render('clerk.report.total.twig', $data);
})->bind('clerk.report.total');

$app->get('/clerk/report/totalmonth:{startY}', function($startY) use ($app) {

    $data = array();
    if (!($startY>2000)and($startY<2050)) $startY=date('Y');
    $startDate = $startY.'-01-01';
    $endDate = date($startY.'-12-t');
    $totalTmp = RDAStaticReport::getTotalByMonth($startDate, $endDate);
    $maleTmp = RDAStaticReport::getTotalByMonth($startDate, $endDate, false, array("sex = 'm'"));
    $femaleTmp = RDAStaticReport::getTotalByMonth($startDate, $endDate, false, array("sex = 'f'"));
    $uglTmp = RDAStaticReport::getTotalByMonth($startDate, $endDate, false, array("impstatus LIKE '%ugl%'"));
    $odaTmp = RDAStaticReport::getTotalByMonthAndExternals($startDate, $endDate, false, false, 'ОДА');
    $oblradaTmp = RDAStaticReport::getTotalByMonthAndExternals($startDate, $endDate, false, false, 'Облрада');
    $mvkTmp = RDAStaticReport::getTotalByMonthAndExternals($startDate, $endDate, false, false, 'МВК');
    $rvkTmp = RDAStaticReport::getTotalByMonthInternal($startDate, $endDate, false, false);

    $arReportFormatted = array();
    $arReportFormatted['header']['term'] = '';
    $arReportFormatted['total']['term'] = 'Всього';
    $arReportFormatted['male']['term'] = 'Чоловіків';
    $arReportFormatted['female']['term'] = 'Жінок';
    $arReportFormatted['ugl']['term'] = 'УГЛ';
    $arReportFormatted['oda']['term'] = 'ОДА';
    $arReportFormatted['oblrada']['term'] = 'Облрада';
    $arReportFormatted['mvk']['term'] = 'МВК';
    $arReportFormatted['rvk']['term'] = 'РВК';
    $data['dates'] = array();
    $sum['total'] = 0;
    $sum['male'] = 0;
    $sum['female'] = 0;
    $sum['ugl'] = 0;
    $sum['oda'] = 0;
    $sum['oblrada'] = 0;
    $sum['mvk'] = 0;
    $sum['rvk'] = 0;
    $sum['t'] = 0;
    $arReportFormatted['header']['data'] = array();
    $arReportFormatted['total']['data'] = array();
    $arReportFormatted['male']['data'] = array();
    $arReportFormatted['female']['data'] = array();
    foreach ($totalTmp as $key => $dItem) {

        $arReportFormatted['header']['data'][] = $dItem['theDate'];
        $data['dates'][] = $dItem['theDate'];
        $arReportFormatted['total']['data'][] = $dItem['num'];
        $arReportFormatted['male']['data'][] = $maleTmp[$key]['num'];
        $arReportFormatted['female']['data'][] = $femaleTmp[$key]['num'];
        $arReportFormatted['ugl']['data'][] = $uglTmp[$key]['num'];
        $arReportFormatted['oda']['data'][] = $odaTmp[$key]['num'];
        $arReportFormatted['oblrada']['data'][] = $oblradaTmp[$key]['num'];
        $arReportFormatted['mvk']['data'][] = $mvkTmp[$key]['num'];
        $arReportFormatted['rvk']['data'][] = $rvkTmp[$key]['num'];
        $sum['total'] += $dItem['num'];
        $sum['male'] += $maleTmp[$key]['num'];
        $sum['female'] += $femaleTmp[$key]['num'];
        $sum['ugl'] += $uglTmp[$key]['num'];
        $sum['oda'] += $odaTmp[$key]['num'];
        $sum['oblrada'] += $oblradaTmp[$key]['num'];
        $sum['mvk'] += $mvkTmp[$key]['num'];
        $sum['rvk'] += $rvkTmp[$key]['num'];
    }
    $arReportFormatted['total']['total'] = $sum['total'];
    $arReportFormatted['male']['total'] = $sum['male'];
    $arReportFormatted['female']['total'] = $sum['female'];
    $arReportFormatted['ugl']['total'] = $sum['ugl'];
    $arReportFormatted['oda']['total'] = $sum['oda'];
    $arReportFormatted['oblrada']['total'] = $sum['oblrada'];
    $arReportFormatted['mvk']['total'] = $sum['mvk'];
    $arReportFormatted['rvk']['total'] = $sum['rvk'];
    $data['report'] = $arReportFormatted;

    return $app['twig']->render('clerk.report.totalmonth.twig', $data);
})->bind('clerk.report.totalmonth');

