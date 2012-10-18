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



abstract class Components {

  abstract function initialize($args);

  protected function display( $args=null, $tpl = 'view.phtml' ) {
    $templater = new AppTemplate();

    $cls = new ReflectionClass($this);
    $component = $cls->getName();
    $pathName = strtolower(str_replace('Component', '', $component));
    $path = ROOT . DS . 'www' . DS . 'app' . DS . 'components' . DS;
    $templater->setViewPath($path . $pathName . DS);

    if(defined('BACKEND')){
      $fname = 'Cback'.$pathName .'_'. $tpl;
    } else {
      $fname = 'Cfront'.$pathName .'_'. $tpl;
    }

    $fname = str_replace(array('/', '..'), array('', ''), $fname);
    $tpl = $tpl;
    return $templater->render( $args, $tpl, $fname);
  }



  protected function redirect($path, $time = null) {
    if(!$time) {
      header('Location: ' . $path);
    } else {
      header('Refresh:' . $time . ';' . $path);
    }
  }


  protected function isXHttp() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
       !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
       strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
      return true;
    }
    return false;
  }



  protected function isPost() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
  }



  protected function isGet() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
  }



  protected function isEmail($email) {
    return preg_match('/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/',$email);
  }


  protected function isImage($type) {
    return preg_match('/(jpeg|jpg|png|gif)/i', $type);
  }


  protected function isArchive($type) {
    return preg_match('/(zip|rar)/i', $type);
  }


  protected function isFile($type) {
    if(!$this->isImage($type) && !$this->isArchive($type)) {
      return true;
    }
    return false;
  }



  protected function post($name, $sanitize = false, $callback = null) {
    if(isset($_POST[$name])) {
      if($sanitize) {
        $res = strip_tags(trim($_POST[$name]));
      } else {
        $res = $_POST[$name];
      }

      if($callback != null) {
        return $callback($res);
      }

      return $res;
    } else {
      return null;
    }

  }



  protected function get($name, $sanitize = false, $callback = null) {

    if($sanitize) {
      $res = strip_tags(trim($_GET[$name]));
    } else {
      $res = $_GET[$name];
    }

    if($callback != null) {
      return $callback($res);
    }

    return $res;
  }



  protected function request($name, $sanitize = false, $callback = null) {

    if($sanitize) {
      $res = strip_tags(trim($_REQUEST[$name]));
    } else {
      $res = $_REQUEST[$name];
    }

    if($callback != null) {
      return $callback($res);
    }

    return $res;
  }




}