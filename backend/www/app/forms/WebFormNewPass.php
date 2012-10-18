<?php

class WebFormNewPass extends PjoyForm {


  function __construct($customError, $hash) {
    $options = array(
      'method' => 'post',
      'class'  => 'native_form',
      'id'  => 'native_form',
      'action' => '/auth/newpass.html?key='.$hash
    );
    $this->create($options);

    $this->label('Новый пароль:', 'newpass');
    $this->input('password', array('name' => 'newpass'),
            'Вы не ввели новый пароль');

    $this->label('Подтвердите новый пароль:', 'confpass');
    $this->input('password', array('name' => 'confpass'),
            'Вы не подтвердили новый пароль');

    $this->button( array('value' => 'Подтвердить',
                     'class' => 'button buttonGreyUi') );

    if( $customError ) {
      $this->setError($customError);
    }

    $this->onSuccess('Войдите в систему, используя форму <a href="/auth/login.html">авторизации</a>');
    $this->onSubmit('/auth/newpass.html?key='.$hash);
  }


}