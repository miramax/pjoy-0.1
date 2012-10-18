<?php



class WebFormEditMenu extends PjoyForm {



  function __construct($parents, $data, $error=false) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/menu/edit/'.$data['id']
      );


      $this->create($options);

      $this->label('Название', 'name');
      $this->text(array('name' => 'name', 'value'=>$data['name']), 'Введите название');

      $this->hidden(array('name' => 'id', 'value'=>$data['id']));

      $this->label('Путь', 'url');
      $this->text(array('name' => 'url', 'value'=>$data['url']), 'Путь не может быть пустым');

      $this->label('Родитель', 'parent');
      $this->select(array('name'=>'parent','value'=>$parents[$data['parentId']]), $parents);

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonGreenUi') );

      if($error){
        $this->setError($error);
      }

      $this->onSuccess('Изменения успешно сохранены');

  }



}