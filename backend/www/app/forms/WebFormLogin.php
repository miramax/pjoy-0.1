<?php



class WebFormLogin extends PjoyForm {



  function __construct($customError) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/login.html'
      );


      $this->create($options);

      $this->label( 'Логин:', 'login' );
      $this->text( array("name" => "login"), ' ');

      $this->label( 'Пароль:', 'password' );
      $this->input( 'password', array("name" => "password"), ' ');

      $this->button( array('value' => 'Войти',
                           'class' => 'button buttonBlackUi') );

      if($customError){
        $this->setError($customError);
      }


      //$this->onSubmit('/auth/');

  }



}