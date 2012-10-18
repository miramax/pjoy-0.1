<?php


class WebFormContentDelete extends PjoyForm {

  function __construct($id) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/content/delete/'
      );


      $this->create($options);

      $this->hidden(array('name' => 'id', 'value' => $id));

      $this->button( array('value' => 'Удалить',
                           'class' => 'button buttonRedUi') );

  }

}