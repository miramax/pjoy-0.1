<?php



class ApplicationController extends Controllers {



  protected $seo;
  protected $js;
  protected $css;
  protected $content;



  public function __construct() {

      $this->content = '';

      $this->seo = array(
          'title' => '',
          'keywords' => '',
          'description' => ''
      );

      $this->css = new PjoyHead('style');
      $this->css->add('/public/css/style.css');

      $this->js = new PjoyHead('script');
      $this->js->init(
                        array(
                              '/public/js/jquery-1.7.2.min.js',
                              '/public/js/navigator.js.php',
                              '/public/js/jquery-ui-1.8.21.min.js',
                              '/public/js/jquery.scrollTo-1.4.2-min.js',
                              '/public/js/script.js',
                        )
              );
  }



  public function main() {

      $data['seo'] = $this->seo;
      $data['css'] = $this->css;
      $data['js'] = $this->js;
      $data['content'] = $this->content;
      echo $this->render( '../layouts/main', $data);
  }



}