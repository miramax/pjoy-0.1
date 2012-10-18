<?php

class CaptchaController extends EmptyController {

  public function indexAction() {

    $files = new DirectoryIterator(STORAGE);
    foreach($files as $file) {
      if( $file->isFile() ) {
        # !more than 5 minutes
        if( (time() - $file->getCTime()) > 300){
          unlink(STORAGE.$file);
        }
      }
    }

    # crete new cache image if not created
    if(!isset($_SESSION['secretCode'])) { #
      $captcha = new PjoyCaptcha;
      $captcha->create();
    } else {
      # else output old cache file
      $file = STORAGE.$_SESSION['secretCode'].'.png';
      if(file_exists($file)) {
        header("Content-Type: image/png");
        header('Content-Length: ' . filesize($file));
        header("Cache-Control: no-cache, must-revalidate");
        header("Cache-Control: post-check=0,pre-check=0");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");

        readfile($file);
      } else {
        # if !file delete session and redirect
        unset($_SESSION['secretCode']);
        $this->redirect('/image.png');
        exit;
      }
    }

  }


  public function refreshAction() {
      if(!$this->isXHttp()){
        exit;
      }
      sleep(1);
      $file = STORAGE.$_SESSION['secretCode'].'.png';
      if(file_exists($file)) {
        unlink($file);
      }
      unset($_SESSION['secretCode']);
      $captcha = new PjoyCaptcha;
      $captcha->create();
      $this->redirect('/image.png');
  }

}