<?php

define( 'BACKEND', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] );
define( 'ROOT',  DOCUMENT_ROOT. '/backend' );
define( 'UPLOADS_FOLDER', DOCUMENT_ROOT . '/public/uploads/' );
set_include_path( get_include_path() . PATH_SEPARATOR .
                  ROOT . DS . '../system' . DS . 'kernel' . DS );


require 'Application.php';

$app = new Application();
$app->run();

# print memory_get_peak_usage() / 1000;