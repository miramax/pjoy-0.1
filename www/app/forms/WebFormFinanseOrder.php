<?php


class WebFormFinanseOrder extends PjoyForm {


  function __construct($error) {
    $options = array(
        'method' => 'post',
        'class' => 'native_form formfinanse',
        'id' => 'native_form',
        'action' => '/ajax/formfinanse/',
        'enctype' => 'multipart/form-data'
    );

    $this->create($options);

    $this->label('Имя: * ', 'name');
    $this->text(array('name' => 'name'),
            'Имя обязательно для заполнения');

    $this->label('Организация: * ', 'company');
    $this->text(array('name' => 'company'),
            'Название организации обязательно для заполнения');

    $this->label('Комментарий к заказу:', 'comments');
    $this->textarea(array('name' => 'comments'));

    $this->label('Прикрепите документ:','attach');
    $this->file(array('name' => 'attach', 'id' => 'attach'));

    $this->label('Введите код с картинки:', 'code');
    $this->captcha('/image.png');
    $this->text( array('name' => 'code', 'class' => 'code-input'),
             'Введите код с картинки');

    $this->button(array('class' => 'submit', 'value' => 'Отправить'));

    if($error) {
      $this->setError($error);
    } else if(!$error && $this->valid() && $this->send()) {
      unset($_SESSION['secretCode']);
    }

    $this->onSuccess('Спасибо, ваш заказ принят');

    $this->onSubmit('/ajax/formfinanse/');

  }


}