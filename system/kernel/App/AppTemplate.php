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



class AppTemplate {



  private $engine = false;

  private $view_path = '';

  private $replace = array();

  private $pattern = array();

  private $info = array();



  public function __construct() {

    $this->info = AppRegistry::init()->run()->getTemplate();
    header('Content-Type: text/html;charset='.$this->info['template']['charset']);
    if ( $this->info['template']['templating'] == '1' ) {
      $this->engine = true;
    }

  }



  public function setViewPath( $path ) {
    $this->view_path = $path;
  }



  public function render( $args, $tpl = null, $fname ) {
    $template = $this->view_path . $tpl;
    if($this->engine == true) {
      $fname = ROOT . $this->info['cache']['dir'] . $fname;
      $Pjoy__file = $this->compile( $template, $fname );
    } else {
      $Pjoy__file = $template;
    }

    if ( count($args) ) {
      foreach( $args as $key => $value ) {
        $$key = $value;
      }
    }

    ob_start();
      include $Pjoy__file;
    return ob_get_clean();
  }



  private function compile( $template, $fname ) {

    if($this->info['template']['cache'] && file_exists($fname)) {
      return $fname;
    }
    $rules = $this->parseRules();
    $this->replace = $rules['replace'];
    $this->pattern = $rules['pattern'];
    $data = preg_filter($this->pattern, $this->replace, file_get_contents($template));
    if( $data == null ) {
      $data = file_get_contents($template);
    }

    file_put_contents( $fname, $data );

    return $fname;

  }



  private function parseRules() {

    $rules = $this->getRules();

    foreach($rules as $key=>$value) {
      $array['pattern'][$key] = $value[0];
      $array['replace'][$key] = $value[1];
    }

    return $array;
  }



  private function getRules() {
   return TplRegexp::init()->rules();
  }


}