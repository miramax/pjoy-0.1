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



class AppRouter {



  private static $routes = array();
  private static $router;



  public static function set( $url, $controller, $action = 'indexAction', array $params = null ) {

    self::$routes[] = array(
        'url' => $url,
        'controller' => $controller,
        'action' => $action,
        'params' => $params
    );

  }



  public static function getRoutes() {
    return self::$routes;
  }



  public function getRoute() {
    return self::$router;
  }



  public static function isRouteExists($url) {
    if($url === null) {
      $url = 'index';
    }
    foreach (self::$routes as $route) {
      if(preg_match($route['url'], $url)) {
        self::$router = $route;
        return true;
      }
      /*if( $route['url'] == $url ) {
        self::$router = $route;
        return true;
      } elseif( $route['url'] == $url. '/' ) {
        self::$router = $route;
        return true;
      } elseif( $route['url'] == '/'.$url ) {
        self::$router = $route;
        return true;
      } elseif( $route['url'] == '/'.$url ) {
        self::$router = $route;
        return true;
      }*/
    }
    return false;
  }



}