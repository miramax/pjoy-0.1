<?php


class PjoyHead {

  private $src = array();
  private $type = '';

  public function __construct($type) {
    $this->type = $type;
  }

  public function add($sourcePath) {
    $this->src[] = $sourcePath;
  }

  public function init(array $src) {
    $this->src = $src;
  }

  public function __toString() {
    $res = array();
    switch($this->type){
      case 'script':
        foreach($this->src as $s)
              $res[] = '<script type="text/javascript" src="'.$s.'"></script>';
      break;

      case 'style' :
        foreach($this->src as $s)
              $res[] = '<link href="'.$s.'" rel="stylesheet" type="text/css" media="all"/>';
      break;

    }

    return implode(PHP_EOL, $res) . PHP_EOL;
  }

}