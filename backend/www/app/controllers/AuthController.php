<?php

class AuthController extends AuthBaseController {


  public function indexAction() {


    $customError = false;

    if ($this->isPost()) {
      sleep(1);
      $user = new User();
      $crypter = new PjoyCrypter(MY_KEY);

      $name = $_POST['login'];
      $password = $_POST['password'];

      if (!empty($name) && !empty($password)) {
        $password = md5($password);
        $result = $user->Login($name, $password);
        if (!$result) {
          $customError = ('Неправильное имя пользователя или пароль');
        } else {
          $name = $crypter->Encryption($name);
          setcookie('_hash', $name . 'pjinf' . $password, (time() + (3600 * 24 * 2)), '/');
          setcookie('_key', $result, ( time() + (3600 * 24 * 2)), '/');
          $this->redirect('/auth/');
        }
      } else {
        $customError = ('Логин и пароль должны быть заполнены');
      }
    }

    $form = new WebFormLogin($customError);

    $data['form'] = $form;
    $this->display('../login/login', $data);
  }


  public function logoutAction() {

    if (isset($_COOKIE['_hash']) && isset($_COOKIE['_key'])) {
      setcookie('_hash', 'null', time() - 10, '/');
      setcookie('_key', 'null', time() - 10, '/');
    }

    $this->redirect('/auth/login.html');
  }


  public function recoveryAction() {


    $customError = false;

    if ($this->isPost()) {

      $email = $this->post('email', true);

      if (!empty($email) && !$this->isEmail($email)) {

        $customError = 'Неправильный E-mail';
      } else if (!empty($email) && $this->isEmail($email)) {

        $user = new User();
        $return = $user->EmailExists($email);
        if ($return) {
          $crypter = new PjoyCrypter(MY_KEY);
          $mail = new PjoyMail();
          $mail->to($email);
          $mail->type('html');
          $mail->subject($_SERVER['HTTP_HOST'] . ': Восстановление доступа');
          $vars['hash'] = $crypter->Encryption($return);
          $mail->body(template('/templates/mail.tpl.phtml', $vars));
          $mail->send();
        } else {
          $customError = "Введеный вами адрес не используется в системе";
        }
      } else {
        $customError = "Укажите E-mail";
      }
    }

    $data['form'] = new WebFormRecovery($customError);
    $this->display('../login/recovery', $data);
  }

  
  function newPasswordAction() {

    $hash = isset($_GET['key']) ? $_GET['key'] :
            $this->redirect('/auth/login.html');
    $user = new User;
    $crypter = new PjoyCrypter(MY_KEY);
    $key = $crypter->Decription($hash);

    if ($user->validKey($key)) {
      $customError = false;

      if ($this->isPost()) {
        $newpass = $this->post('newpass');
        $confpass = $this->post('confpass');

        if ($newpass && $confpass) {

          if (($newpass === $confpass)) {
            $user->recoveryPassword($key, md5($newpass));
          } else {
            $customError = 'Введенные пароли не совпадают';
          }
        }
      }
      $data['form'] = new WebFormNewPass($customError, $hash);
      $this->display('../login/newpass', $data);
    } else {
      $this->redirect('/auth/login.html');
    }
  }

}