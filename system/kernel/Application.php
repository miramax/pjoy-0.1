<?php
/**
* Pjoy Framework v0.8
* An open source application development framework for PHP 5.3 or newer
* @package		Pjoy Framework
* @version    0.8
* @author     Gradusov Andrey
* @copyright	Copyright (c) - 2012, Gradusov Andrey
* @license		http://need_site/license/
* @link       http://need_site/downloads/
*/

setlocale(LC_ALL, "ru_RU.UTF-8");
date_default_timezone_set("Europe/Moscow");

require  'App'   . DS .
         'AppAutoload.php';

require  'AppConstants.php';

require  'AppFunctions.php';


class Application {


  public function run() {
      session_start();
      $autoload = new AppAutoload();
      $autoload->run();
      $filter = new AppXSSFilter();
      $filter->filter();
      $request = new AppRequest();
      $path = $request->get('path');

      require ROOT . DS . 'www' . DS . 'conf' . DS . 'routes.php';

      $application = new AppFrontController($path);
      $application->run();
  }



}