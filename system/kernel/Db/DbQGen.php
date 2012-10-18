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



class DbQGen {
  
  
  /**
   * cont curr tableName
   * @var string 
   */
  private $table;
  
  /**
   * contains correct query after gen
   * @var string
   */
  private $query = "";
  
  /**
   * auxiliary constant (quote) for wrapping
   * names in SQL
   */
  const WRAP = "`";
  
  
  
  /**
   *
   * @param array $options 
   */
  public function __construct( array $options ) {
    
    $this->table = ( isset($options['suffix']) && $options['suffix'] != '' )? $options['suffix'] . '_' . $options['table']:  $options['table'];
    $this->table = $this->wrap( $this->table );
    if( isset($options['select']) ) {
      $this->select_action( $options['select'] );
      
    } 
    if( isset($options['insert']) ) {
      $this->insert_action( $options['insert'] );
      
    } 
    
    if( isset($options['update']) ) {
      $this->update_action( $options['update'] );
      
    } 
    if( isset($options['delete']) ) {
      $this->delete_action( $options['delete'] );
      
    } 
    
    if( isset($options['count']) ) {
      $this->count_pseudo_action( $options['count'] );
      
    } 
    if( isset($options['rand']) ) {
      $this->rand_pseudo_action($options['rand']);
      
    }
    
    
    if( isset($options['where']) ) {
      $where = $options['where']['args'];
      $type = isset( $options['where']['type'] )? $options['where']['type']:  "AND";
      $this->where_condition( $where, $type );
      
    }
    
    if( isset($options['order']) ) {
      $by = $options['order'][0];
      $type = isset( $options['order'][1] )? $options['order'][1]:  "ASC";
      $this->order_condition( $by, $type );
      
    }
    
    if( isset($options['limit']) ) {
      $from = $options['limit'][0];
      $to = isset( $options['limit'][1] )? $options['limit'][1]:  false;
      $this->limit_condition( $from, $to );
      
    }
    
    
    
  }
  
  
  
  /**
   *
   * @param array $select 
   */
  private function select_action( array $select ) {
    
    $this->query = "SELECT ";
    $count = count($select);
    
    switch($count) {
      case 0: trigger_error('Select must contains one or more values', E_USER_WARNING);
        break;
      case 1: $this->query .= ($select[0] == '*')?$select[0]:$this->wrap( $select[0] );
        break;
      default: $this->query .= $this->wrap( join( $this->wrap(','), $select) );
    }
    
    $this->query .= " FROM " . $this->table;
    
  }
  
  
  
  /**
   *
   * @param array $insert 
   */
  private function insert_action( array $insert ) {
    
    if( count($insert) ) {
      $this->query = "INSERT INTO " . $this->table . "(";
      $keys = array();

      foreach( $insert as $key => $value ) {
        $keys[] = $key;
      }
      $this->query .= $this->wrap( join( $this->wrap(','), $keys) );
      $this->query .= ") VALUES(";

      foreach ($keys as $key) {
        $this->query .= ':' . $key . ',';
      }
      $this->query = rtrim($this->query, ',');
      $this->query .= ')';
    }
    
  }
  
  
  
  /**
   *
   * @param array $update 
   */
  private function update_action( array $update ) {
    if( count($update) ) {
      $this->query = "UPDATE " . $this->table . " SET ";

      foreach( $update as $key => $value ) {
        $this->query .= $key . "=:" . $key . ",";
      }

      $this->query = rtrim($this->query, ",");
    }
  }
  
  
  
  /**
   *
   * @param array $delete 
   */
  private function delete_action( array $delete ) {
    
    $this->query = "DELETE FROM " . $this->table;
    
  }
  
  
  
  /**
   *
   * @param array $count 
   */
  private function count_pseudo_action( array $count ) {
    
    $this->query = "SELECT COUNT(*) FROM " . $this->table;
    
  }
  
  
  
  /**
   * hightly not recommend to use RAND() in MySQL queryes
   * use 'where' condition - in(php_rand(1,10),php_rand(11,20),php_rand(21,30)) - more fast and flexible
   * 
   * @param int $limit 
   */
  private function rand_pseudo_action( $limit ) {
    
    $this->query = "SELECT * FROM " . $this->table .
                   " ORDER BY RAND()";
    
    $this->limit_condition($limit);
    
  }
  
  
  
  /**
   *
   * @param array $where
   * @param string $type 
   */
  private function where_condition( array $where, $type='AND' ) {
    if( count($where) ) {
      $this->query .= " WHERE ";
      foreach($where as $key => $value) {
        $this->query .= $key . "=:where_" . $key . " $type ";
      }
      $this->query = rtrim($this->query, " $type ");
    }
  }
  
  
  
  /**
   *
   * @param string $by
   * @param string $type 
   */
  private function order_condition( $by, $type='ASC' ) {
    if($by) {
      $this->query .= " ORDER BY " . $by . " $type";
    }
  }
  
  
  
  /**
   *
   * @param string $from
   * @param string $to 
   */
  private function limit_condition( $offset=1, $limit=false ) {
    
    $this->query .= " LIMIT $offset";
    if( $limit ) {
      $this->query .= ",$limit";
    }
    
  }
  
  
  
  
  /**
   *
   * @return string 
   */
  public function __toString() {
    return $this->query;
  }
  
  
  
  /**
   *
   * @param string $name
   * @return string 
   */
  private function wrap( $name ) {
    return self::WRAP . $name . self::WRAP;
  }
  
  
  /**
   * unset current query string
   * 
   */
  public function __destruct() {
    $this->query = "";
    unset( $this->query );
  }
  
}


/*
 * 
 * 
 * options = array(
 *  
 * 
 * 
 * table => 'tableName',
 * 
 * 
 * 
 *  select => array('field_1','field_2','field_3', 'field_4'),
 * 
 * 
 *  
 *  insert => array('field_1' => 'value_1', 'field_2' => 'value_2', 'field_3' => 'value_3'),
 * 
 * 
 * 
 *  update => array('field_1' => 'value_1', 'field_2' => 'value_2', 'field_3' => 'value_3'),
 * 
 * 
 * 
 *  delete => array(1),
 * 
 * 
 * 
 *  where => array('args' => array(
 *        'field_1' => 'value_1', 'field_2' => 'value_2'
 * ),
 *        'type' = 'AND'
 * )
 * 
 * 
 * 
 * limit => array($from, $to)
 * 
 * 
 * 
 * order => array($by, $type),
 * 
 * 
 * count => array(1)
 *    
 *                               );
 * 
 */
/*
$opt = array(
    'select' => array('field_1', 'field_2'),
    'table' => 'tableName',
    'suffix' => 'suffix',
    'insert' => array('name_1' => 'val_1', 'name_2' => 'val_2'),
    'update' => array('name_1' => 'val_1', 'name_2' => 'val_2'),
    'delete' => array(1),
    'where' => array('name_1' => 'val_1', 'name_2' => 'val_2'),
    'limit' => array(1,2)
);


$select = array(
    'table' => 'Users',
    'select' => array('name', 'password'),
    'where' => array('args' => array('id' => 72, 'role' => 'administrator')),
    'order' => array('id'),
    'limit' => array(1,2),
);

$delete = array(    
    'table' => 'Users',
    'delete' => array(1),
    'where' => array('args' => array('id' => 72)),
    'limit' => array(1),
    );

$insert = array(
    'table' => 'Users',
    'insert' => array('name'=>'Vasya', 'password' => '#Q$@$@!@'),
    'limit' => array(1),
);

$update = array(
    'table' => 'Users',
    'update' => array('name'=>'Vasya', 'password' => '#Q$@$@!@'),
    'where' => array('args' => array('id' => 72)),
    'limit' => array(1),
);

$rand = array(
    'table' => 'Users',
    'rand' => 20,
);

$count = array(
    'table' => 'Users',
    'count' => array(1),
    #'where' => array('args' => array('id' => 72)),
);

$query = new QGen( $count );
print($query);
 */