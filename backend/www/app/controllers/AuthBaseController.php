<?php




class AuthBaseController extends Controllers {


  public function __construct() {
    if($this->_isAuth()) {
      $this->redirect('/auth/');
    }

      $this->css = new PjoyHead('style');
      $this->css->add('/public/css/login.css');
      $this->js = new PjoyHead('script');
      $this->js->init(
                array('/public/js/jquery-1.7.2.min.js',
                      '/public/js/login.js')
              );
  }

  public function main(){

    $data['js'] = $this->js;
    $data['css'] = $this->css;
    $data['content'] = $this->content;
    echo $this->render( '../login/main', $data);
  }


  private function _isAuth() {
    if ( isset( $_COOKIE['_hash'] ) && isset( $_COOKIE['_key'] ) ) {
      $crypt = new PjoyCrypter( MY_KEY );
      $values = AppHelper::explodeCookieHash( $_COOKIE['_hash'] );

      $name = $crypt->Decription( $values[0] );
      $password = $values[1];
      $key = (int)( $_COOKIE['_key'] );

      $user = new User();

      if ( $user->Validate( $name, $password, $key ) ) {
        return true;

      } else {
        setcookie('_hash', 'null', time()-10, '/');
        setcookie('_key', 'null', time()-10, '/');

      }

    }

    return false;
  }

}