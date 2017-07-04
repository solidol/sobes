<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$app->get('/files/img/{name}', function($name) use ($app) {
    $prefix = $app['upload_folder'] . '/';
    $full_name = $prefix . $name;

    $thumb_name = '';
    $thumb_width = 320;
    $thumb_height = 240;

    $size = 'original'; //!!!!!!!!!

    if (!file_exists($full_name)) {
        throw new \Exception('File not found');
    }

    switch ($size) {
        default:
        case 'small':
            $thumb_name = $prefix . 'small_' . $name . '.jpg';
            $thumb_width = 320;
            $thumb_height = 240;
            break;

        case 'medium':
            $thumb_name = $prefix . 'medium_' . $name . '.jpg';
            $thumb_width = 1024;
            $thumb_height = 768;
            break;
    }

    $out = null;

    if ('original' == $size) {
        //$out = new BinaryFileResponse($full_name);
    } else {
        /* if ( !file_exists( $thumb_name ) )
          {
          $app['imagine']->open($full_name)
          ->thumbnail(
          new Imagine\Image\Box($thumb_width,$thumb_height),
          Imagine\Image\ImageInterface::THUMBNAIL_INSET)
          ->save($thumb_name);
          }
         */
        //$out = new BinaryFileResponse($thumb_name);
    }
    
    $stream = function () use ($full_name) {
        readfile($full_name);
    };
    return $app->stream($stream, 200, array(
                'Cache-Control' => 's-maxage=3600',
                'Content-Type' => mime_content_type($full_name),
                'Content-length' => filesize($full_name),
                'Content-Disposition' => 'attachment; filename="' . basename($full_name),
                'Content-length', filesize($full_name)
    ));
})->bind('files.get.img');
