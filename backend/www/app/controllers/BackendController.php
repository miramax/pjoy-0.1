<?php

class BackendController extends Controllers {

  protected $userInfo = array();
  protected $allUsers = array();

  public function __construct() {
    usleep(500000);
    if (!$this->_isAuth()) {
      $this->redirect('/auth/login.html');
    }

    header("Pragma: no-cache");
    header("Cache: no-cache");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");


    $this->css = new PjoyHead('style');
    $this->css->init(array(
        '/backend/public/css/style.css',
        '/backend/public/js/redactor/css/redactor.css',
        '/backend/public/js/imageCrop/jquery.imagecrop.css'
    ));

    $this->js = new PjoyHead('script');
    $this->js->init(array(
        '/backend/public/js/jquery-1.7.2.min.js',
        '/backend/public/js/jquery-ui/jquery-ui-1.8.22.custom.min.js',
        '/backend/public/js/redactor/redactor.js',
        '/backend/public/js/redactor/langs/ru.js.php',
        '/backend/public/js/jquery.scrollTo-1.4.2-min.js',
        '/backend/public/js/imageCrop/jquery.imagecrop.js',
        '/backend/public/js/jquery.translit.js',
        '/backend/public/js/system.js'
    ));
  }

  public function main() {

    $data['css'] = $this->css;
    $data['js'] = $this->js;
    $data['content'] = $this->content;
    if ($this->isXHttp()) {
      echo $this->render('../layouts/empty', $data);
    } else {
      echo $this->render('../layouts/main', $data);
    }
  }

  private function _isAuth() {
    if (isset($_COOKIE['_hash']) && isset($_COOKIE['_key'])) {
      $crypt = new PjoyCrypter(MY_KEY);
      $values = AppHelper::explodeCookieHash($_COOKIE['_hash']);

      $name = $crypt->Decription($values[0]);
      $password = $values[1];
      $key = (int) ( $_COOKIE['_key'] );

      $user = new User();
      $this->userInfo = $user->Validate($name, $password, $key);
      if ($this->userInfo) {
        $user->clearResult();
        $user->find(':all');
        $this->allUsers = $user->result();
        return true;
      } else {
        setcookie('_hash', 'null', time() - 10, '/');
        setcookie('_key', 'null', time() - 10, '/');
      }
    }

    return false;
  }

  protected function buttons(array $buttons) {

    $vars = array();

    for ($i = 0; $i < count($buttons); $i++) {
      $s = explode('::', $buttons[$i]);
      $vars[$i]['name'] = $s[0];
      $vars[$i]['url'] = $s[1];
      $vars[$i]['class'] = $s[2];
    }

    return template('/templates/button.tpl.phtml', $vars);
  }

}