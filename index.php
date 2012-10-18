<?php

setlocale(LC_ALL, "ru_RU.UTF-8");
date_default_timezone_set("Europe/Moscow");

define( 'DS', DIRECTORY_SEPARATOR );
define( 'DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] );
define( 'ROOT',  DOCUMENT_ROOT );
define( 'UPLOADS_FOLDER', DOCUMENT_ROOT . '/public/uploads/' );
define( 'STORAGE', DOCUMENT_ROOT.'/www/tmp/storage/' );
set_include_path( get_include_path() . PATH_SEPARATOR .
                  ROOT . DS . 'system' . DS . 'kernel' . DS );


require 'Application.php';

$app = new Application();
$app->run();