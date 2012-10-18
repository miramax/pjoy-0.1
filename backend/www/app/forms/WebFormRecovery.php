<?php

class WebFormRecovery extends PjoyForm {

  function __construct($customError) {
      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/forgot.html'
      );

      $this->create($options);

      $this->label('E-mail:', 'email');
      $this->text( array('name' => 'email'));

      $this->button(array('value' => 'Отправить', 'class' => 'button buttonGreyUi'));

      if($customError){
        $this->setError($customError);
      }

      $this->onSuccess('Инструкции отправлены на указанный вами E-mail');

      $this->onSubmit('/auth/forgot.html');
  }

}