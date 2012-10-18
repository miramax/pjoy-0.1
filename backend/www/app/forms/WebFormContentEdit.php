<?php



class WebFormContentEdit extends PjoyForm {



  function __construct($data, $categoryes) {

      $options = array(
        'method' => 'post',
        'class'  => 'native_form',
        'id'  => 'native_form',
        'action' => '/auth/content/edit/'.$data['id']
      );


      $this->create($options);


      $this->label('Название:', 'name');
      $this->text(array('name' => 'name', 'value' => $data['name']),
              'Пожалуйста, придумайте название страницы');

      $this->label('Категория:', 'categoryId');
      $this->select(array('name' => 'categoryId', 'value' => $data['categoryId']), $categoryes);

      $this->html('<span class="show_more">расширенная настройка</span><br/>');
      $this->html('<div class="more_options">');


      $this->label('Тайтл:', 'title');
      $this->text(array('name' => 'title', 'value' => $data['title']));


      $this->label('Ключевые слова:', 'keywords');
      $this->text(array('name' => 'keywords', 'value' => $data['keywords']));


      $this->label('Описание:', 'description');
      $this->text(array('name' => 'description', 'value' => $data['description']));


      $this->label('URL адрес:', 'alias');
      $this->text(array('name' => 'alias', 'value' => $data['alias']));


      $this->label('Статус: ', 'status');
      $data['status'] = $data['status'] == 1?'Отображать':'Не отображать';
      $this->select(array('name' => 'status', 'value' => $data['status']), array('Отображать','Не отображать'));


      $this->label('Комментирование: ', 'isComment');
      $data['isComment'] = $data['isComment'] == 1?'Включено':'Выключено';
      $this->select(array('name' => 'isComment', 'value' => $data['isComment']), array('Выключено','Включено'));


      $this->html('</div><br/>');

      $this->label('Содержимое:', 'content');
      $this->textarea(array('name' => 'content', 'style' => 'height: 575px', 'value' => $data['content']),
              'Содержимое страницы не может быть пустым');

      $this->hidden(array('name' => 'id', 'value' => $data['id']));

      $this->html('<br/><br/>');
      $this->inlineLabel('Главная страница?', 'mainPage');
      if($data['alias'] == '/') {
        $this->checkbox(array('name' => 'mainPage', 'class' => 'inline', 'checked' => 'checked'));
      } else {
        $this->checkbox(array('name' => 'mainPage', 'class' => 'inline'));
      }
      $this->html('<br/><br/>');

      $this->button( array('value' => 'Сохранить',
                           'class' => 'button buttonBlueUi') );

      $this->onSuccess('Изменения успешно сохранены');
      $this->onSuccess('Вы можете вернуться назад к <a href="/auth/content/" class="data-load">списку</a> страниц');

      $this->onSubmit('/auth/content/edit/'.$data['id']);

  }



}