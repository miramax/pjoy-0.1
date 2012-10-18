<?php

class WebFormCategoryAdd extends PjoyForm {



  function __construct( $error=false) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/category/add/'
      );


      $this->create($options);

      $this->label('Название', 'name');
      $this->text(array('name' => 'name'), 'Введите название');

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonGreenUi') );

      if($error){
        $this->setError($error);
      }

      $this->onSuccess('Изменения успешно сохранены');

  }



}