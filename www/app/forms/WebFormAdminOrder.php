<?php


class WebFormAdminOrder extends PjoyForm {


  function __construct($error) {
    $options = array(
        'method' => 'post',
        'class' => 'native_form formadmin',
        'id' => 'native_form',
        'action' => '/ajax/formadmin/',
    );

    $this->create($options);


    $this->label('Тема сообщения:', 'theme');
    $this->select(array('name' => 'theme'),
                  array('Вопрос', 'Предложение','Ошибка на сайте'));

    $this->label('E-mail (для ответа): * ', 'email');
    $this->text(array('name' => 'email'),
            'Вы не указали адрес электронной почты');

    $this->label('Сообщение: * ', 'message');
    $this->textarea(array('name' => 'message'));

    $this->label('Введите код с картинки:', 'code');
    $this->captcha('/image.png');
    $this->text(array('name' => 'code', 'class' => 'code-input'),
            'Введите код с картинки');

    $this->button(array('class' => 'submit', 'value' => 'Отправить сообщение'));

    if($error) {
      $this->setError($error);
    } else if(!$error && $this->valid() && $this->send()) {
      unset($_SESSION['secretCode']);
    }

    $this->onSuccess('Спасибо, мы обязательно вам перезвоним');

    $this->onSubmit('/ajax/formadmin/');

  }


}