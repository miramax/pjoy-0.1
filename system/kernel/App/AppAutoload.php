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



require  'App'   . DS .
         'AppHelper.php';

class AppAutoload {




  public function run() {
    $this->register('core');
    $this->register('controller');
    $this->register('model');
    $this->register('forms');
    $this->register('libs');
    $this->register('components');
  }




  private function core( $cls ) {

    $exp = AppHelper::explodeByUpperCase( $cls );
    $path = $exp[0] . DS . $cls . '.php';

    if ( file_exists( DOCUMENT_ROOT . DS . 'system' . DS . 'kernel' . DS .$path ) ) {
      require_once $path;
    } elseif( file_exists( DOCUMENT_ROOT . DS . 'system' . DS . 'kernel' . DS . $cls . '.php' ) ) {
      require_once $cls . '.php';
    }

  }




  private function controller( $cls ) {

    $filename = ROOT . DS . 'www' . DS . 'app' . DS . 'controllers' . DS . $cls . '.php';

    if ( file_exists($filename) ) {
      require_once $filename;
    }

  }


  private function components( $cls ) {

    $e=AppHelper::explodeByUpperCase($cls);
    $dir=strtolower($e[0]);
    $filename = ROOT . DS . 'www' . DS . 'app' . DS . 'components' . DS . $dir . DS . 'component.php';

    if ( file_exists($filename) ) {
      require_once $filename;
    }

  }




  private function model( $cls ) {

    $filename = ROOT . DS . 'www' . DS . 'app' . DS . 'models' . DS . $cls . '.php';
    if ( file_exists($filename) ) {
      require_once $filename;
    }

  }



  private function libs( $cls ) {
    $filename = DOCUMENT_ROOT . DS . 'system' . DS . 'extras' . DS . $cls . '.php';
    if(file_exists($filename)) {
      require_once $filename;
    }
  }



  private function forms( $cls ) {
    $filename = $filename = ROOT . DS . 'www' . DS . 'app' . DS . 'forms' . DS . $cls . '.php';
    if ( file_exists($filename) ) {
      require_once $filename;
    }
  }



  private function register( $methodName ) {
    spl_autoload_register( array($this, $methodName) );
  }




}