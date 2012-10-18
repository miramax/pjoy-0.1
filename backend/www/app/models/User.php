<?php

class User extends Models {



  public function Login($name, $password) {
    $this->name = $name;
    $this->find(':one');
    $row = $this->result();

    if( empty($row) ) {
      return false;
    }

    if( $row['password'] !== $password ) {
      return false;
    }

    $cookieId = rand(99,300);

    $this->cookieId = $cookieId;
    $this->id = $row['id'];
    $this->lastDate = time();
    $this->update();

    if( $this->result() === 1 ) {
      return $cookieId;
    }
      return false;
  }



  public function Validate( $name, $password, $key ) {
    $this->name = $name;
    $this->password = $password;
    $this->cookieId = $key;
    $this->find(':one');
    $row = $this->result();
    if ( !empty($row) ) {
      $this->id = $row['id'];
      $this->lastDate = time();
      $this->update();
      $this->clearResult();

      return $row;

    }

    return false;
  }



  public function EmailExists( $email ) {
    $this->email = $email;
    $this->find(':one');
    $row = $this->result();
    if( !empty($row) ) {
      return $row['name'].'exploding'.$row['id'];
    }
    return false;
  }



  public function validKey($key) {
    $key = @explode('exploding', $key);
    if(isset($key[0]) && isset($key[1])) {
         $this->name = $key[0];
         $this->id = $key[1];
         $this->find(':one');
         $res = $this->result();
         if ( !empty($res) ) {
           return true;
         }
    }

    return false;
  }


  public function recoveryPassword($key, $pass) {
    $key = @explode('exploding', $key);
    if(isset($key[0]) && isset($key[1])) {
      $this->id = $key[1];
      $this->password = $pass;
      $this->update();
    }
  }



}