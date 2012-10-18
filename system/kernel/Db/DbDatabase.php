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



abstract class DbDatabase extends DbConnection {



  /**
   * Main ActiveRecord options.
   * This need for building valid
   * query before binding or execution
   *
   * @var array
   */
  protected $options = array();

  /**
   * current query.
   * contains a current query.
   *
   * @var string
   */
  protected $query = "";

  /**
   * contains last query to db in this table
   *
   * @var string
   */
  protected $lastQuery;

  /**
   * in this prop we save a result after execution or fetching
   *
   * @var mixed
   */
  protected $result = array();

  /**
   * auxiliary filter
   *
   * @var string
   */
  protected $fetch_result_type = ':one';

  /**
   * auxiliary prop
   *
   * @var string
   */
  protected $binding_type = 'default';

  /**
   * copy of options prop with key 'where'
   *
   * @var array
   */
  protected $where = array();

  /**
   * flag for custom query
   *
   * @var boolean
   */
  protected $fetching = false;



  /**
   * call Connection::__construct() and ... etc ...
   */
  public function __construct() {
    parent::__construct();
    $this->set_defaults();
  }



  /**
   * fetching data from statement
   *
   * @param (object)PDOStatement $stmt
   * @param string $type
   */
  protected function fetch( PDOStatement $stmt ) {
    $this->result = array();
    if( $this->fetch_result_type == ":one" || isset($this->options['count']) ) {
        $this->result = $stmt->fetch();
    } elseif( $this->fetch_result_type == ":all" ) {
        while( $row = $stmt->fetch() ) {
               $this->result[] = $row;
        }
    }
  }



  /**
   * binding values from array of props
   *
   * @param PDOStatement $stmt
   * @param string $type
   */
  protected function binding( PDOStatement $stmt ) {
    if( $this->binding_type == "default" ) {
      foreach( $this->options['params'] as $key=>$value ) {
        $stmt->bindValue( ":" . $key , $value, PDO::PARAM_STR );
      }
    } elseif( $this->binding_type == "custom" ) {
      foreach( $this->options['params'] as $key=>$value ) {
        $stmt->bindValue( $key, $value, PDO::PARAM_STR );
      }
    }

    if(isset( $this->where['args'] )) {
      foreach( $this->where['args'] as $key=>$value ) {
        $stmt->bindValue( ':where_'.$key, $value, PDO::PARAM_STR );
      }
    }

    $stmt->execute();
  }



  /**
   * prepare query and create statement
   *
   * @return (object)PDOStatement $stmt
   */
  protected function prepare() {
    $this->query = AppHelper::filter_string($this->query);
    $stmt = self::$pdo->prepare($this->query);
    if(!$stmt) {
      trigger_error('Proble with preparing query: '.$this->query, E_USER_WARNING);
    }
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    return $stmt;
  }



  /**
   * return affectet rows from last db query
   *
   * @param (object)PDOStatement $stmt
   */
  protected function affected_rows( PDOStatement $stmt ) {
    $this->result = $stmt->rowCount();
  }



  /**
   * return result from last query
   *
   * @return mixed
   */
  protected function getResult() {
    return $this->result;
  }



  /**
   * main executing method
   *
   * @return mixed
   */
  protected function execution() {

    if( empty($this->query) ) {
      $this->query = new DbQGen($this->options);
    }

    $stmt = $this->prepare();
    $this->binding($stmt);
    $this->lastQuery = $this->query;
    $this->query = null;

    if( isset($this->options['select']) || isset($this->options['rand']) ) {
      $this->fetch($stmt);

      if(isset($this->options['select'])){
        unset($this->options['select']);
      } else {
        unset($this->options['rand']);
      }

    }elseif( isset($this->options['count']) ) {
      $this->fetch($stmt);
      unset($this->options['count']);
      #var_dump($this->result);die;
      $this->result = $this->result['COUNT(*)'];
      return $this->result;

    }elseif( $this->binding_type == 'custom' && $this->fetching == true ) {
      $this->fetch($stmt);

    } else {
      $this->affected_rows($stmt);

    }


    return $this->result;
  }



  /**
   * Magic PHP Method
   *
   * @param string $name
   * @return mixed
   */
  public function __get( $name ) {
    return $this->options['params'][$name];
  }



  /**
   * Magic PHP Method
   *
   * @param string $name
   * @param mixed $value
   */
  public function __set( $name, $value ) {
    $this->options['params'][$name] = $value;
  }



  /**
   * Return an lastn query
   *
   * @return string
   */
  public function lastQuery() {
    return $this->lastQuery;
  }



  /**
   * Return result after execution
   * @return mixed
   */
  public function result() {
    $this->set_defaults();
    return $this->result;
  }


  public function clearResult() {
      $this->result();
  }



  /**
   * setup defaults props
   */
  protected function set_defaults() {

    $this->options = array(
          "table" => isset($this->options['table'])?$this->options['table']:'',
          "params" => array(),
          "primary_key" => isset($this->options['primary_key'])?$this->options['primary_key']:'',
    );


    if($this->info['SUFFIX'] != 'null') {
      $this->options['suffix'] = $this->info['SUFFIX'];
    }

  }




  /*
   * abstract layer for ActiveRecord class...
   */
  abstract public function find( $filter_type );
  abstract public function insert();
  abstract public function update();
  abstract public function delete();
  abstract public function fields( $names );
  abstract public function where( array $args );
  abstract public function limit( $from = 1, $to = null );
  abstract public function query( $sequel );
  abstract public function bind( array $args );



}