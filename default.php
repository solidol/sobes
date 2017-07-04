<?php


require_once __DIR__ . '/app.php';


/**
  Главная
 */
$app->get('/', function () use ($app) {
//$user = $app['user.manager']->createUser('mamager1@srda.ks.ua', '1111', 'Admin 1', array('ROLE_MANAGER'));

//$app['user.manager']->insert($user);
//    die();

    if ($app['security']->isGranted('ROLE_MANAGER')) {
        return $app->redirect($app['url_generator']->generate('manager.start'));
    } elseif ($app['security']->isGranted('ROLE_CLERK')) {
        return $app->redirect($app['url_generator']->generate('clerk.start'));
    } elseif ($app['security']->isGranted('ROLE_ADMIN')) {
        return $app->redirect($app['url_generator']->generate('admin.start'));
    } else
        return $app->redirect($app['url_generator']->generate('user.login'));
})->bind('homepage');



require_once __DIR__ . '/controllers/includer.php';

//$app['http_cache']->run();
$app->run();
