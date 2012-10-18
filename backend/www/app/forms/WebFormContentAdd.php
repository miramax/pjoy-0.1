<?php



class WebFormContentAdd extends PjoyForm {



  function __construct($categoryes, $id) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/content/add/'
      );


      $this->create($options);


      $this->label('Название:', 'name');
      $this->text(array('name' => 'name'),
              'Пожалуйста, придумайте название страницы');

      $this->label('Категория:', 'categoryId');
      $this->select(array('name' => 'categoryId'), $categoryes);


      $this->html('<span class="show_more">расширенная настройка</span><br/>');
      $this->html('<div class="more_options">');

      $this->label('Тайтл (meta-title):', 'title');
      $this->text(array('name' => 'title'));

      $this->label('Ключевые слова (meta-keywords):', 'keywords');
      $this->text(array('name' => 'keywords'));

      $this->label('Описание (meta-description):', 'description');
      $this->text(array('name' => 'description'));

      $this->label('URL адрес (alias):', 'alias');
      $this->text(array('name' => 'alias'));

      $this->label('Статус: ', 'status');
      $this->select(array('name' => 'status'), array('Отображать','Не отображать'));

      $this->label('Комментирование: ', 'isComment');
      $this->select(array('name' => 'isComment'), array('Выключено','Включено'));

      $this->html('</div><br/>');


      $this->label('Содержимое:', 'content');
      $this->textarea(array('name' => 'content', 'style' => 'height: 575px'),
              'Содержимое страницы не может быть пустым');

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonBlueUi') );
      if($id) {
        $this->onSubmit('/auth/content/edit/'.$id.'/?success=1');
      }


  }



}