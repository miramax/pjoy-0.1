<?php



class WebFormCategoryEdit extends PjoyForm {



  function __construct($data, $error=false) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/category/edit/'.$data['id']
      );


      $this->create($options);

      $this->label('Название', 'name');
      $this->text(array('name' => 'name', 'value'=>$data['name']), 'Введите название');

      $this->hidden(array('name' => 'id', 'value'=>$data['id']));

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonGreenUi') );

      if($error){
        $this->setError($error);
      }

      $this->onSuccess('Изменения успешно сохранены');

  }



}