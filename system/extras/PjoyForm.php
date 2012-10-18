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



class PjoyForm implements ArrayAccess {


  private $form = "";
  private $identity;
  private $fields = '';
  private $options = array();
  public  $valid = true;
  private $success = '';
  private $errors = "";
  private $captcha = false;
  private $container = array();



  protected function create(array $options) {
    $this->options = $options;
    $o = new ReflectionObject($this);
    $this->identity = $o->getName();

    $this->container['begining'] = "<form ".
                                   $this->keyAsVal($this->options).
                                   ">" . PHP_EOL;
    $this->hidden(array('name' => $this->identity, 'value' => 1));
    $this->container['ending'] = $this->container[$this->identity]. '</form>';
  }



  protected function input($name, array $props, $onerror = NULL) {

    if($this->send() && isset($props['name'])) {
      $props['value'] = $_REQUEST[$props['name']];
      if($onerror && empty($props['value'])) {
        $this->valid = false;
        $this->errors($onerror);
        $props['class'] = isset($props['class'])?
                  $props['class'] . ' empted':'empted';
      }
    }
    if(!isset($props['id']) && isset($props['name'])) {
      $props['id'] = $props['name'];
    }
    $this->container[$props['name']] = "<input type=\"$name\" " .
                              $this->keyAsVal($props) .
                              "/>" . PHP_EOL;
    $this->fields .= $this->container[$props['name']];
  }


  protected function text(array $props, $onerror = NULL) {
    $name = 'text';
    if($this->send() && isset($props['name'])) {
      $props['value'] = $_REQUEST[$props['name']];
      if($onerror && empty($props['value'])) {
        $this->valid = false;
        $this->errors($onerror);
        $props['class'] = isset($props['class'])?
                          $props['class'] . ' empted':'empted';
      }
    }
    if(!isset($props['id']) && isset($props['name'])) {
      $props['id'] = $props['name'];
    }
    $this->container[$props['name']] = "<input type=\"$name\" " .
                              $this->keyAsVal($props) .
                              "/>" . PHP_EOL;
    $this->fields .= $this->container[$props['name']];

  }



  protected function file(array $props, $onerror = NULL) {
    $name = 'file';
    if($this->send() && isset($props['name'])) {
      $props['value'] = isset($_FILES[$props['name']]['name'])?$_FILES[$props['name']]['name']:'';
      if($onerror && empty($props['value'])) {
        $this->valid = false;
        $this->errors($onerror);
      }
    }
    if(!isset($props['id']) && isset($props['name'])) {
      $props['id'] = $props['name'];
    }
    $this->container[$props['name']] = "<input type=\"$name\" " .
                              $this->keyAsVal($props) .
                              "/>" . PHP_EOL;
    $this->fields .= $this->container[$props['name']];

  }



  protected function textarea(array $props, $onerror = NULL) {

    $value = isset($props['value'])? $props['value'] : '';
    unset($props['value']);
    if($this->send() && isset($props['name'])) {
      $value = $_REQUEST[$props['name']];
      if($onerror && empty($value)) {
        $this->valid = false;
        $this->errors($onerror);
        $props['class'] = isset($props['class'])?
                          $props['class'] . ' empted':'empted';
      }
    }
    if(!isset($props['id'])) {
      $props['id'] = $props['name'];
    }
    if(isset($props['label'])) {
      $this->fields .= $this->label($props['label'], $props['id']);
    }
    $this->container[$props['name']] = "<textarea " .
                              $this->keyAsVal($props) . '>' . $value .
                              "</textarea>" . PHP_EOL;
    $this->fields .= $this->container[$props['name']];

  }



  protected function select(array $props, array $values, $onerror = NULL) {
      $str = '';
      if($this->send() && isset($props['name'])) {
        $props['value'] = $_REQUEST[$props['name']];
        if($onerror && empty($props['value'])) {
          $this->valid = false;
          $this->errors($onerror);
        }
      }
      if(!isset($props['id']) && isset($props['name'])) {
        $props['id'] = $props['name'];
      }
      $str .= "<select ";
      $str .= $this->keyAsVal($props);
      $str .= ">" . PHP_EOL;
      foreach($values as $value) {
          if(isset($props['value']) && ($props['value'] == $value)) {
              $str .= "<option selected>" .$value. "</option>" . PHP_EOL;
          } else{
              $str .= "<option>" .$value. "</option>" . PHP_EOL;
          }
      }
      $str .= '</select>';
      $this->container[$props['name']] = $str;
      $this->fields .= $str;
  }




  protected function captcha($imgPath) {
     $version = isset($_COOKIE['iVersion'])?$_COOKIE['iVersion']:1;
     $this->container['captcha'] = '<img class="code-image" src="'.$imgPath.'?v='.$version.'" alt="code" />
                                    <span class="code-refresh">обновить</span>'. PHP_EOL;
     $this->fields .= $this->container['captcha'];
  }



  protected function button(array $props) {

      $this->container['submit'] = "<input type=\"submit\" " .
                                   $this->keyAsVal($props).
                                   "/>" . PHP_EOL;
      $this->fields .= $this->container['submit'];
  }



  protected function hidden(array $props) {
      return $this->input("hidden", $props);
  }



  protected function html($html) {
    $this->fields .= $html;
  }



  protected function radio(array $props, $onerror = NULL) {
    $name = "radio";
    $checked = '';
    if($this->send() && isset($props['name'])) {
      $val = $_REQUEST[$props['name']];
      if($val == $props['value']){
          $checked = "checked=\"checked\"";
      }
      if($onerror && !($val == $props['value'])) {
        $this->valid = false;
        $this->errors($onerror);
      }
    }
    if(!isset($props['id']) && isset($props['name'])) {
      $props['id'] = $props['name'];
    }
    $this->container[$props['name']] = "<input type=\"$name\" $checked".
                              $this->keyAsVal($props).
                              "/>" . PHP_EOL;
    $this->fields .= $this->container[$props['name']];
  }



  protected function checkbox(array $props, $onerror = NULL) {
      $name = "checkbox";
      $checked = '';
      if($this->send() && isset($props['name'])) {
        $val = isset($_REQUEST[$props['name']])?1:0;
       if($val == 1){
           $checked = "checked=\"checked\"";
       }
        if($onerror && !($val == $props['value'])) {
          $this->valid = false;
          $this->errors($onerror);
        }
      }
      if(!isset($props['id']) && isset($props['name'])) {
        $props['id'] = $props['name'];
      }
      $this->container[$props['name']] = "<input type=\"$name\" $checked".
                                $this->keyAsVal($props).
                                "/>" . PHP_EOL;
      $this->fields .= $this->container[$props['name']];
  }


  public function errors($msg) {
    if(!isset($this->container['errors'])) {
      $this->container['errors'] = '';
    }
    $this->container['errors'] .= '<p>' . $msg . '</p>' . PHP_EOL;
    $this->errors = $this->container['errors'];
  }


  public function onSuccess($msg) {
    if(!isset($this->container['success'])) {
      $this->container['success'] = '';
    }
    if(isset($_COOKIE[$this->identity.'_success']) && $_COOKIE[$this->identity.'_success'] == 'true'){
      $this->container['success'] .= '<p>'.$msg.'</p>';
      $this->success .= '<p>'.$msg.'</p>';
    }
  }


  private function getFields() {
    return $this->fields;
  }


  protected function onSubmit($url) {
    if($this->send() && $this->valid()){
      header("Location: " . $url);
    }
  }


  protected function label($label, $for) {
    $this->container["label_".$for] = '<label for="'.$for.'">'.$label.'</label>' . PHP_EOL;
    $this->fields .= $this->container["label_".$for];
  }

  protected function inlineLabel($label, $for) {
    $this->container["label_".$for] = '<label class="inline" for="'.$for.'">'.$label.'</label>' . PHP_EOL;
    $this->fields .= $this->container["label_".$for];
  }

  public function send() {
    $id = isset($_REQUEST[$this->identity])?$_REQUEST[$this->identity]:0;
    if($_SERVER['REQUEST_METHOD'] == strtoupper($this->options['method'])
            &&  $id == 1){
      return true;
    } else {
      return false;
    }
  }

  public function valid() {
      return $this->valid;
  }

  private function keyAsVal($props) {
    $string = '';
    foreach($props as $k=>$v) {
      if($k != 'label'){
        $string .= $k . "=" . '"' .$v. '" ';
      }
    }
    return rtrim($string);
  }


  public function setError($msg) {
    $this->valid = false;
    $this->errors($msg);
  }


  public function __toString() {
    if($this->send() && $this->valid()) {
      setcookie($this->identity . '_success', 'true', time()+9, '/');
    } else {
      setcookie($this->identity . '_success', 'true', time()-10, '/');
    }
    $this->form .= !empty($this->errors)?'<div class="form_error" id="form_response">'.PHP_EOL.$this->errors.'</div>' . PHP_EOL:'';
    $this->form .= !empty($this->success)?'<div class="form_success" id="form_response">'.PHP_EOL.$this->success.'</div>'. PHP_EOL:'';
    $this->form .= $this->container['begining'];
    $this->form .= $this->getFields();
    $this->form .= '</form>';
    return $this->form;

  }


  public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
  }


  public function offsetExists($offset) {
        return isset($this->container[$offset]);
  }


  public function offsetUnset($offset) {
        unset($this->container[$offset]);
  }


  public function offsetGet($offset) {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
  }


}


?>