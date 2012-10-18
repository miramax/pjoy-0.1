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



class AppRegistry {
  
  
  /**
   *
   * @var type
   */
  static private $__instance = null;
  
  
  /**
   *
   * @var type 
   */
  private $__options = array();
  
  
  /**
   * 
   */
  const FILE_NAME = 'config.ini';
  
  /**
   * 
   */
  private function __construct() { }
  
  
  
  /**
   *
   * @return type 
   */
  public function init() {
    if ( self::$__instance === null ) {
         self::$__instance = new self;
    }
    return self::$__instance;
  }
  
  
  
  /**
   *
   * @return AppRegistry 
   */
  public function run() {
    $this->getOptions();
    return $this;
  }

  
  
  /**
   *
   * @return type 
   */
  private function databaseSection() {
    return $this->__options['database.registry'];
  }
  
  
  
  /**
   *
   * @return type 
   */
  private function cacheSection() {
    return $this->__options['cache.registry'];
  }
  
  
  
  /**
   *
   * @return type 
   */
  private function templateSection() {
    return $this->__options['template.registry'];
  }

  
  
  /**
   *
   * @return type 
   */
  public function getDSN() {
    $options = $this->databaseSection();

    foreach($options as $key=>$value) {
      $$key = $value;
    }
    
    $port = ($port != '')?'port='.$port.';':'';
    
    $dsn = $driver.':'.'host='.$host.';'.$port.'dbname='.$database;
    
    $suffix = ($suffix != '')?  $suffix:  'null';
    
    return array(
        'DSN' => $dsn,
        'USERNAME' => isset($username)?$username:false,
        'PASSWORD' => isset($password)?$password:false,
        'SUFFIX'  => isset($suffix)?$suffix:false,
        'CHARSET' => isset($charset)?$charset:false
    );
    
  }
  
  
  
  /**
   *
   * @return type 
   */
  public function getTemplate() {
    
    $options['template'] = $this->templateSection();
    $options['cache'] = $this->cacheSection();
    return $options;
    
  }
  
  
  
  /**
   * 
   */
  private function getOptions() {
    $options = array();
    $options = $this->readFile();
    $this->__options = $options;
  }
  
  
  
  /**
   *
   * @return type 
   */
  private function readFile() {
    $filename = ROOT . DS . 'www' . DS . 'conf' . DS . self::FILE_NAME;
    return parse_ini_file($filename, true);
  }
  
  
  
  
}