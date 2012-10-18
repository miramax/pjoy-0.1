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



class PjoyPagination {

  private $quantity = 0;
  private $onPage = 0;
  private $currentPage = 1;

  private $result = array();

  private static $instance;



  private function __construct() {
    //
  }



  public static function init() {
    if(self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }



  public function quantity($quantity) {
    $this->quantity = $quantity;

    return $this;
  }



  public function listen($request) {
    if($request != null) {
      $this->currentPage = (int)$request;
    }

    return $this;
  }



  public function onPage($onPage) {
    $this->onPage = (int)$onPage;

    return $this->calculate();
  }


  private function calculate() {

    $offset = ($this->currentPage - 1) * $this->onPage;
    $limit = $this->onPage;
    $pagesCount = ceil($this->quantity / $this->onPage);

    $this->result = array(
        "offset" => (int)$offset,
        "limit" => (int)$limit,
        "pagesCount" => (int)$pagesCount,
        "currentPage" => (int)$this->currentPage,
    );

    return $this->result;
  }

}


/**
 *

$request = isset($_REQUEST['page'])?$_REQUEST['page']:null;
$result = Pagination::init()
        ->quantity(7)
        ->listen($request)
        ->onPage(5);
 */