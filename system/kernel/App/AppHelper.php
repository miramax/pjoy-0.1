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



class AppHelper {



  /**
   * exploding classname by uppercase name
   * @param string $className
   * @return array $explode
   */
  static public function explodeByUpperCase ( $className ) {
    $explode = @preg_split( "/(?=[A-Z])/",
                            $className, 0,
                            PREG_SPLIT_NO_EMPTY );
    return $explode;

  }



  /**
   * filter string with native php function
   * @param string $string
   * @return string
   */
  static public function filter_string ( $string ) {
    return filter_var($string, FILTER_SANITIZE_STRING);
  }


  static public function explodeCookieHash ($string) {
    $res = explode('pjinf', $string);
    return $res;
  }

  static public function getUrlPath($url) {
    return str_replace('http://'.$_SERVER['HTTP_HOST'], '',$url);
  }


  static function NotFound() {
    header("HTTP/1.0 404 Not Found");
    echo file_get_contents(ROOT.'/www/app/views/404.php');
    exit;
  }

}