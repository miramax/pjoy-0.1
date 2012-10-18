<?php


class WebFormCallbackOrder extends PjoyForm {


  function __construct($error) {
    $options = array(
        'method' => 'post',
        'class' => 'native_form formcallback',
        'id' => 'native_form',
        'action' => '/ajax/formcallback/',
    );

    $this->create($options);

    $this->label('Имя: * ', 'name');
    $this->text(array('name' => 'name'),
            'Имя обязательно для заполнения');

    $this->label('Телефон: * ', 'phone');
    $this->text(array('name' => 'phone'),
            'Телефон обязателен для заполнения');

    $this->label('Введите код с картинки:', 'code');
    $this->captcha('/image.png');
    $this->text(array('name' => 'code', 'class' => 'code-input'),
            'Введите код с картинки');

    $this->button(array('class' => 'submit', 'value' => 'Перезвонить'));

    if($error) {
      $this->setError($error);
    } else if(!$error && $this->valid() && $this->send()) {
      unset($_SESSION['secretCode']);
    }

    $this->onSuccess('Спасибо, мы обязательно вам перезвоним');

    $this->onSubmit('/ajax/formcallback/');

  }


}