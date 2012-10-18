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



abstract class DbConnection {
  

  
  /**
   * contains a PDO object
   * @var (object)PDO 
   */
  protected static $pdo;
  
  /**
   * info from config.ini file
   * @var array 
   */
  protected $info;
  
  #protected static $call = null;

  
  /**
   * init config(info) array and set static PDO propertie
   */
  public function __construct() {
      $this->info = $this->getConfig();
      self::$pdo = new PDO($this->info['DSN'],
                           $this->info['USERNAME'],
                           $this->info['PASSWORD']);

      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if($this->info['CHARSET']) {
       $stmt = self::$pdo->prepare('SET NAMES '.$this->info['CHARSET']);
       $stmt->execute();
      }
  }
  
  
  
  /**
   * return a DSN array from config.ini (database section)
   * @return array 
   */
  private function getConfig() {
    return AppRegistry::init()->run()->getDSN();
  }

  
  
  
  
  
  /**
   * abstract methods for Database class
   */
  abstract protected function fetch(PDOStatement $stmt );
  abstract protected function prepare();
  abstract protected function binding( PDOStatement $stmt );
  abstract protected function affected_rows( PDOStatement $stmt );
  abstract protected function set_defaults();
  
  
  
}