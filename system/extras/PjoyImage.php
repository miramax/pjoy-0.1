<?php
/**
* Pjoy Framework v0.8
* An open source application development framework for PHP 5.3 or newer
* @package		Pjoy Framework
* @version    0.8
* @author     Gradusov Andrey
* @copyright	Copyright (c) - 2012, Gradusov Andrey
* @license		http://need_site/license/
* @link       http://need_site/downloads/
*/



class PjoyImage {

  private $type = false;
  private $filetypes = array('','gif','jpeg','png');
  private $filename = '';
  private $filepath = '';
  private $Ctype = '';
  private $crop = false;
  private $new_width = null;
  private $new_height = null;
  private $custom = false;
  private $width = null;
  private $height = null;
  private $destination = '';
  private static $instance = null;
  private static $error;

  // max side size in pixels
  const MAX_PX = 900;

  // quality
  const QUALITY = 80;


  /**
   * jpg, png, gif (default jpeg)
   * @param string $type
   */
  public function type( $type ) {
     $this->type = $type;
     return $this;
  }


  private function __construct() {
    //
  }


  public static function init() {
    if(self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }


  /**
   * Type here field name from Form
   * @param string $fieldname
   * @return Image object
   */
  public function get($filepath) {
    $this->filepath = $filepath;
    $this->destination = $filepath;

    $this->filename = preg_replace('/\/.*\//i', '', $this->filepath);

    return $this;
  }


  /**
   * Set path for upload and new name
   * if you need
   * @param string $path
   * @param string $newname
   * @return Image object
   */
  public function put($path, $newname = null) {
    if($newname == null) {
      $this->destination = $path . $this->filename;
    } else {
      preg_match_all('/\.(png|jpeg|gif|bmp|jpg)/i' , $this->filename, $ext);
      if(isset($ext[0][0])){
        $newname = str_replace($ext[0][0], '', $this->filename) . $newname .$ext[0][0];
        $this->destination = $path . $newname;
      } else {
        self::$error = 'Incorrect file tipe';
      }
    }

    return $this;
  }



  /**
   * insert width and height;
   * @param int $width
   * @param int $height
   * @return Image object
   */
  public function size($width, $height) {
    $this->new_width=$width;
    $this->new_height=$height;
    $this->custom = true;

    return $this;
  }


  /**
   * put width param
   * @param int $width
   */
  public function width($width) {
    $this->new_width = $width;
    $this->custom = true;

    return $this;
  }



  public function crop() {
    $this->crop = true;
    return $this;
  }



  /**
   * put height parametr
   * @param int $width
   */
  public function height($height) {
    $this->new_height = $height;
    $this->custom = true;

    return $this;
  }


  public function imageCrop($width, $height, $x, $y) {
        $boundary = $width;
        $dst_w = $width;
        $dst_h = $height;

        if ($dst_w > $dst_h)
        {
            $dst_h = $dst_h * $boundary / $dst_w;
            $dst_w = $boundary;
        }
        else
        {
            $dst_w = $dst_w * $boundary / $dst_h;
            $dst_h = $boundary;
        }

        $this->prepare();

        // Create a new image from the source image path
        $func = "imagecreatefrom" . $this->Ctype;
        $src_image = $func($this->destination);

        // Create the output image as a true color image at the specified size
        $dst_image = imagecreatetruecolor($dst_w, $dst_h);

        // Copy and resize part of the source image with resampling to the
        // output image
        imagecopyresampled($dst_image, $src_image, 0, 0, $x,
                           $y, $dst_w, $height, $width,
                           $height);

        // Output the image to browser
        $func = "image" . $this->Ctype;
        $func($dst_image, $this->destination);
  }


  public function create() {
    $res = $this->prepare();
    if($res){
      $res = $this->calculate();

      if($res){
        $this->imagecreate();
        return str_replace($_SERVER["DOCUMENT_ROOT"], '', $this->destination);
      } else {
        return false;
      }
    } else {
      return false;
    }
    return true;
  }


  public static function error() {
    return self::$error;
  }


  /**
   * preparing image
   */
  private function prepare() {
    if($this->filepath != $this->destination) {
      copy($this->filepath, $this->destination);
    }

      list($this->width, $this->height, $type) = getimagesize($this->destination);
      $this->Ctype = $this->filetypes[$type];
      if(!$this->Ctype) {
        self::$error = 'Incorrect file type';
        return false;
      }
        return true;
  }



  /**
   * private function for calculate sizes
   */
  private function calculate() {
      if(!$this->custom && !$this->crop) {

          if($this->width > self::MAX_PX || $this->height > self::MAX_PX) {

            if( $this->width > $this->height )
              {
                $diff = $this->width / $this->height;
                $this->new_width = self::MAX_PX;
                $this->new_height = self::MAX_PX / $diff;
              }
            else if( $this->width < $this->height )
              {
                $diff = $this->height / $this->width;
                $this->new_height = self::MAX_PX;
                $this->new_width = self::MAX_PX / $diff;
              }
            else
              {
                $this->new_height = $this->new_width = self::MAX_PX;
              }

            } else {
                $this->new_height = $this->height;
                $this->new_width = $this->width;
                return false;
            }

       } else {
         if(!$this->new_width || !$this->new_height) {
          if (!$this->new_height){
               $this->new_height = $this->new_width/($this->width/$this->height);
          }
          if (!$this->new_width){
               $this->new_width = $this->new_height/($this->height/$this->width);
          }
         } else {
            if( $this->new_width > $this->new_height )
              {
                $diff = $this->width / $this->height;
                $this->new_width = self::MAX_PX;
                $this->new_height = self::MAX_PX / $diff;
              }
            else if( $this->new_width < $this->new_height )
              {
                $diff = $this->height / $this->width;
                $this->new_height = self::MAX_PX;
                $this->new_width = self::MAX_PX / $diff;
              }
            else
              {
                $this->new_height = $this->new_width;
              }
         }
       }

       if($this->crop == true){
          $min = $this->width;
          if ($this->width > $this->height) $min = $this->height;
          $this->new_width = $this->new_height = $min;
       }
         return true;
  }



  /**
   * create image with current properties
   */
  private function imagecreate() {
      $func = "imagecreatefrom" . $this->Ctype;
      $image = $func($this->destination);
      $new_image = imagecreatetruecolor($this->new_width, $this->new_height);
      if(!$this->crop) {
        imagecopyresampled($new_image, $image, 0, 0, 0, 0, $this->new_width, $this->new_height, $this->width, $this->height);
      } else {
        imagecopy($new_image, $image, 0, 0, 0, 0, $this->new_width, $this->new_height);
      }
      $this->crop = false;
        if($this->type && ($this->type != 'jpeg')) {
            unlink($this->destination);
            $this->destination = preg_replace('/\.(png|jpeg|gif|bmp|jpg)/i', '.'.$this->type, $this->destination);
            $func = "image" . $this->type;
            return $func($new_image, $this->destination);
        } else {
            return imagejpeg($new_image, $this->destination, self::QUALITY);
        }
  }


}

?>