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



class AppRequest {



  /**
   *
   * @param type $type
   * @param type $filter
   * @return type
   */
  public function request($type = null, $filter=false) {

    if ( !$type ) {
      return $this->clearArr($_REQUEST, $filter);
    } elseif($type == ':get'){
      return $this->clearArr($_GET, $filter);
    } elseif($type == ':post') {
      return $this->clearArr($_POST, $filter);
    }

  }



  /**
   *
   * @param type $key
   * @param type $filter
   * @return type
   */
  public function post($key, $filter = null) {

    if (isset($_POST[$key])){
      return $this->clearVar($_POST[$key], $filter);
    } else {
      return null;
    }

  }



  /**
   *
   * @param type $key
   * @param type $filter
   * @return type
   */
  public function get($key, $filter = null) {
    if (isset($_GET[$key])){
      return $this->clearVar($_GET[$key], $filter);
    } else {
      return null;
    }
  }




  /**
   *
   * @return type
   */
  public function isPost() {

    return $_SERVER['REQUEST_METHOD'] == 'POST';

  }




  /**
   *
   * @return type
   */
  public function isGet() {

    return $_SERVER['REQUEST_METHOD'] == 'GET';

  }




  /**
   *
   * @param type $var
   * @param type $filter
   * @return type
   */
  private function clearVar($var, $filter) {
    switch($filter) {
      case ':str': $filter = FILTER_SANITIZE_STRING; $type = 'filter';
        break;
      case ':int': $var = (int)$var; return $var; $type = 'filter';
        break;
      case ':email' : $filter = FILTER_SANITIZE_EMAIL; $type = 'filter';
        break;
      case null: return $var;
      default : $type = 'regexp';
    }

    if($type != 'regexp') {
      return filter_var($var, $filter);
    } else {
      if (preg_match( $filter , $var) ) {
        return $var;
      } else {
        return false;
      }
    }

  }




  /**
   *
   * @param array $arr
   * @param type $filter
   * @return array
   */
  private function clearArr( array $arr, $filter  ) {

    if(!$filter) {

      return $arr;

    } else {

      foreach($arr as $key => $value) {
        $arr[$key] = filter_var( $value, FILTER_SANITIZE_STRING );
      }

    }

    return $arr;
  }





}