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



class TplCache {
  
  
  /*
  private $cache_name; # name of file
  
  private $cache_dir; # where cache will saving
  
  private $template;
  
  private $compile = false;
  
  
  
  
  public function __construct( $template ,$cache_dir ) {
    $this->template = $template;
    $this->cache_dir = $cache_dir;
    
    $this->nameGen( $this->template );
  }
  
  
  
  
  public function createSource( $data) {
    $this->oldFileRemove();
    file_put_contents( $this->cache_name , $data );
    return $this->getSource();
  }
  
  
  
  
  public function getSource() {
    return $this->cache_name;
  }

  
  public function compile( ) {
      if ( $this->compile ) {
          return $this->cache_name;
      }
        return false;
  }
  
  
  
  
  private function nameGen( $file_path ) {
    $this->cache_name = md5_file( $file_path );
    $this->unic = str_replace('/', '_', str_replace( ROOT, '', $file_path));
    $this->cache_name = $this->cache_dir . substr($this->cache_name, 0, 5) . '_' . $this->unic;
  }
  
  
  
  
  private function oldFileRemove() {
    foreach(scandir($this->cache_dir) as $file) {
      if( strstr($file, $this->unic) ) {
        unlink($this->cache_dir . $file);
      }
    }
  }

  
  */
  
}