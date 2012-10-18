<?php



class WebFormMenuAdd extends PjoyForm {



  function __construct($parents, $error=false) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/menu/add/'
      );


      $this->create($options);

      $this->label('Название', 'name');
      $this->text(array('name' => 'name'), 'Задайте имя для пункта меню');

      $this->label('Путь', 'url');
      $this->text(array('name' => 'url'), 'Путь не может быть пустым');

      $this->label('Родитель', 'parent');
      $this->select(array('name'=>'parent'), $parents);

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonGreenUi') );

      if($error){
        $this->setError($error);
      }

  }



}