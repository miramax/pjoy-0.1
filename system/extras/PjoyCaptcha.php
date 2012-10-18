<?php

class PjoyCaptcha {

  private $string;
  private $font;
  private $width = 80;
  private $height = 35;
  private $cacheFile = false;

  public function __construct() {

    $this->font = DOCUMENT_ROOT . '/public/arial.ttf';

    for ($i = 0; $i < 5; $i++) {
      $this->string .= chr(rand(97, 122));
    }

      $_SESSION['secretCode'] = $this->string;
      $this->cacheFile = STORAGE.$this->string.'.png';

  }

  public function create() {
    $image = imagecreatetruecolor($this->width, $this->height);

      $white_color = imagecolorallocate($image, 255, 255, 255);

      $red=rand(110,255);
      $green=rand(120,255);
      $blue=rand(130,255);
      $text_color=imagecolorallocate($image, 255-$red, 255-$green, 255-$blue);

    imagefilledrectangle($image, 0, 0, 200, 100, $white_color);

    /* generate random lines in background */
		for( $i=0; $i<3; $i++ ) {
			imageline($image, mt_rand(30,$this->width),
                        mt_rand(10,$this->height),
                        mt_rand(30,$this->width),
                        mt_rand(20,$this->height),
                $text_color);
		}

    imagettftext($image, 16, rand(-7, 7),
                             rand(7, 15),
                             rand(20,25),
                             $text_color,
                 $this->font,
                 $_SESSION['secretCode']);

    header("Content-Type: image/png");
    header("Cache-Control: no-cache, must-revalidate");
    header("Cache-Control: post-check=0,pre-check=0");
    header("Cache-Control: max-age=0");
    header("Pragma: no-cache");

    imagepng($image);
    imagepng($image, $this->cacheFile);
    exit;
  }

  public function getCode() {
    return $_SESSION['secretCode'];
  }

}