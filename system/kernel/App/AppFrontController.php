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



class AppFrontController {



  private $path;
  private $routes = array();
  private $controller = "";
  private $method = "";
  private $params = array();




  public function __construct( $path ) {
    $this->path = $path;
    $this->routes = null;
    $this->prepare($path);
  }




  public function run() {
    if(class_exists($this->controller)){
      $controllerObject = new ReflectionClass($this->controller);

      try{
        $action = new ReflectionMethod($this->controller, $this->method);
      } catch(Exception $e){
        AppHelper::NotFound();
      }

      if ( $controllerObject->hasMethod($this->method) ) {
           $controller = new $this->controller;
           if (!empty($this->params)) {
             call_user_func_array( array($controller, $this->method), $this->params);
           } else {
             if(count($action->getParameters())==0)
                $controller->{$this->method}();
             else
               AppHelper::NotFound();
           }

           $controller->main();
        }
      } else {
        AppHelper::NotFound();
    }
  }




  private function prepare( $url ) {

    if( AppRouter::isRouteExists($url) ){
      $route = AppRouter::getRoute();
      $this->controller = $route['controller'];
      $this->method = $route['action'];
      $this->params = $route['params'];
      return;
    }

    if( $url ) {
      $url = rtrim($url, '/');
      $params = @explode('/', $url);
      if(class_exists('indexController') ) {
        $indexController = new ReflectionClass('indexController');
      } else {
        $this->controller = $params[0] . 'Controller';
        $this->method = ( isset($params[1]) )? $params[1] . 'Action' : 'indexAction';
        for($i=2,$length=count($params);$i<$length; $i++) {
          if( isset($params[$i]) ) {
            $this->params[] = $params[$i];
          }
        }
        return;
      }
      if(!$indexController->hasMethod($params[0] . 'Action')) {
        $this->controller = $params[0] . 'Controller';
        $this->method = ( isset($params[1]) )? $params[1] . 'Action' : 'indexAction';
        for($i=2,$length=count($params);$i<$length; $i++) {
          if( isset($params[$i]) ) {
            $this->params[] = $params[$i];
          }
        }

      } else {
        $this->controller = 'indexController';
        $this->method = $params[0] . 'Action';

        for($i=1,$length=count($params);$i<$length; $i++) {
          if( isset($params[$i]) ) {
            $this->params[] = $params[$i];
          }
        }

      }
      return;
    } else {
      $this->controller = 'indexController';
      $this->method = 'indexAction';
      return;
    }

  }


}