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
class PjoyFile {

  protected static $instance = null;
  private $name;
  private $path;
  private $file;
  private $type;
  private $filesize;
  private $tmp;
  private $extensions = array('jpeg', 'jpg', 'doc', 'docx', 'png', 'bmp', 'gif', 'pdf', 'xls', 'mp3', 'psd', 'txt', 'rtf', 'odt', 'zip', 'rar');
  static private $error = false;
  const MAX_SIZE = 5000000;

  private function __construct() {
    //
  }

  public static function init() {
    self::$error = false;
    if (self::$instance === null) {
      self::$instance = new self;
    }
    return self::$instance;
  }

  public function get($name) {
    $this->name = $name;
    if (isset($_FILES[$this->name]['name'])) {
      $this->file = $_FILES[$this->name]['name'];
      preg_match_all('~\.(' . implode('|', $this->extensions) . ')~i', $this->file, $this->type);
      $this->tmp = $_FILES[$this->name]['tmp_name'];
    }
    return $this;
  }

  public function put($path) {
    if (isset($_FILES[$this->name]['name'])) {
      $this->path = $path . $this->file;
      return $this->upload();
    } else {
      return false;
    }
  }

  static public function errors() {
    if (!empty(self::$error)) {
      return self::$error;
    } else {
      return false;
    }
  }

  private function upload() {
    if (file_exists($this->path) && !is_dir($this->path)) {
      #print $this->path;
      unlink($this->path);
    }
    $this->filesize = $_FILES[$this->name]['size'];
    if ($this->filesize <= self::MAX_SIZE) {
      if (!empty($this->type[1][0]) && (in_array(strtolower($this->type[1][0]), $this->extensions))) {
        move_uploaded_file($this->tmp, $this->path);
        return array(
            'filepath' => $this->path,
            'filename' => $this->file,
            'filetype' => $this->type[1][0],
            'filesize' => $this->filesize
        );
      } else {
        self::$error = 'Недопустимый формат файла';
        return false;
      }
    } else {
      self::$error = 'Превышен допустимый размер файла';
      return false;
    }
  }

  public static function delete($path) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $path;
    if (is_file($path)) {
      unlink($path);
    }
  }

  public static function copy($source, $dest) {
    if (is_file($source)) {
      copy($source, $dest);
    }
  }

}

?>