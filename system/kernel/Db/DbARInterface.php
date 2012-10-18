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



abstract class DbARInterface extends DbDatabase {



  /**
   * Call an parent construct and initialize props
   *
   */
  public function __construct() {
    parent::__construct();
    $this->init();
  }



  /**
   * Set {insert} action in query
   *
   * @param string $fetch_type
   * @return mixed
   */
  public function find( $fetch_type = ":one" ) {

    $this->fetch_result_type = $fetch_type;

    if( isset($this->options['params']) && !isset($this->options['where'])) {
      $this->where( $this->options['params'] );
    }

    if( ! isset($this->options['select']) ) {
      $this->options['select'] = array('*');
    }

    if($fetch_type == ":one") {
      $this->options['limit'] = array(1);
    }

    return $this->execution();

  }



  /**
   * insert current public props into db
   *
   * @return mixed
   */
  public function insert() {

    $this->where = false;
    unset($this->options['where']);

    $this->options['insert']
            = $this->options['params'];

    return $this->execution();

  }



  /**
   * update current public props into db
   *
   * @return mixed
   */
  public function update() {

    if( ! isset($this->options['where']['args']) ) {
      $this->where (array($this->options['primary_key'] =>
              $this->options['params'][$this->options['primary_key']]));
    }

    $this->options['update'] =
            $this->options['params'];

    return $this->execution();

  }



  /**
   * Delete rows from db
   *
   * @return mixed
   */
  public function delete() {

    if( ! isset($this->options['where']['args']) ) {
      $this->where($this->options['params']);
    }

    $this->options['delete'] = array(1);

    return $this->execution();

  }



  /**
   * Set a {where} statement in query
   *
   * @param array $args
   * @param string $type
   */
  public function where( array $args = null , $type = ":and") {

    $this->options['where']['args'] = $args;
    $this->options['where']['type'] = str_replace(':', '',strtoupper($type));
    $this->where = $this->options['where'];
    $this->options['params'] = array_diff_assoc($this->options['params'], $args);
  }



  /**
   * Set a {limit} statement in query
   *
   * @param int $from
   * @param int $to
   */
  public function limit( $from = 1, $to = null ) {

    $this->options['limit'][] = (int)$from;
    $this->options['limit'][] = ($to!=null)?(int)$to:null;

  }



  /**
   * Set an {order} statement in query
   *
   * @param string $by
   * @param string $filter
   */
  public function order($by, $filter = ':asc') {
    $filter = str_replace(':', '', strtoupper($filter) );
    $this->options['order'] = array($by, $filter);
  }



  /**
   * Set a custom query to db, if query contains custom vars
   * this method return ActiveRecord object for binding this
   *
   * @param string $sequel
   * @return mixed $result
   */
  public function query( $sequel ) {

    $this->fetch_result_type = ":all";

    if( preg_match("/(select|SELECT)/i", $sequel ) ) {
      $this->fetching = true;
      $this->binding_type = "custom";
    }

    if($this->info['SUFFIX']!=null){
      $sequel = preg_filter('/(\#\_)/i', $this->info['SUFFIX'].'_', $sequel);
    }else {
      $sequel = preg_filter('/(\#\_)/i', '', $sequel);
    }

    $this->query = $sequel;
    if( strstr($sequel, ':') ) {

        return $this;
    }

    return $this->execution();

  }



  /**
   * Catch variables from array and convert it into public props
   *
   * @param array $array
   */
  public function assign(array $array) {
    foreach($array as $key => $value) {
      $this->options['params'][$key] = $value;
    }
  }

  /**
   * Binding custom values
   *
   * @param array $args
   * @return ActiveRecord
   */
  public function bind( array $args ) {

    $this->binding_type = "custom";
    $this->options['params'] = $args;

    return $this->execution();
  }



  /**
   * Set in sql query fields that we want to select
   *
   * @param string $names
   * @return ActiveRecord object
   */
  public function fields( $names ) {

    if( strstr($names, ',') ) {
      $fields = explode(',',$names);

      foreach($fields as $field) {
        $this->options['select'][] = trim($field);
      }

    } else {
      $this->options['select'] = array($names);
    }

    return $this;

  }



  /**
   * Fetch a random rows from table with limit
   *
   * @param integer $limit
   * @return array
   */
  public function rand($limit = 1) {

    if($limit == 1) {
      $this->fetch_result_type = ':one';
    } else {
      $this->fetch_result_type = ':all';
    }

    $this->options['rand'] = $limit;

    return $this->execution();

  }



  /**
   * Get count rows from table
   *
   * @return int
   */
  public function count() {

    $this->options['count'] = array(1);
    return $this->execution();
  }



  /**
   * Return true if after query table has changed something
   *
   * @return boolean
   */
  public function affect() {

    if( (int)$this->result ) {
      return true;
    }
      return false;

  }



  /**
   * initialize default protected props
   *
   */
  private function init() {

    $table = false;
    $pk = "";

    if( isset($this->tableName) ) {
      $table = $this->tableName;
    }
    if( isset($this->primaryKey) ) {
      $pk = $this->primaryKey;
    } else {
      $pk = "id";
    }

    if( ! $table ) {
      $cls = new ReflectionClass($this);
      $name = $cls->getName();
      if( strstr($name, 'odel') ) {
        $name = AppHelper::explodeByUpperCase($name);
        $table = $name[0];
      } else {
        $table = $name;
      }
    }

    $this->options['table'] = strtolower($table);
    $this->options['primary_key'] = $pk;
  }

  public function getOptions() {
      return $this->options;
  }


}