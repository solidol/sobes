<?php

/**
 * Template for a local configuration file with data that should not be committed to version control.
 *
 * Copy this to local.php and customize it for the local environment.
 */
$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname' => 'rda_docs',
    'user' => 'root',
    'password' => '',
    'charset' => 'utf8',
);

// enable the debug mode
$app['debug'] = true;
