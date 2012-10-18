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



/**
 * example: import("core.libs.pagination");
 * @param string $path
 */
function import($path) {

  preg_match_all('/([a-z]+)/i', $path, $package);
  $package_path = $package[0][0];
  switch($package_path) {
    case 'library' : $path = DOCUMENT_ROOT . "/system/libs/" . $package[0][1] . '.php';
      break;
    case 'webforms' : $path = ROOT . "/www/app/forms/" . $package[0][1] . '.php';
      break;
    default : $path = DOCUMENT_ROOT . "/system/libs/" . $package[0][1] . '.php';
  }

  if( file_exists($path) ) {
    require_once($path);
  } else {
    die("Package '" . $path . "' - Not Found!");
  }
}


function template($path, array $vars = null) {
  if(is_array($vars)) {
    foreach($vars as $k=>$v) {
      $$k = $v;
    }
  }

  ob_start();
    require ( ROOT. "/www/app/views".$path );
  return ob_get_clean();
}


function PjoyDate($timestamp, $short = false, $getTime = true) {
  $date = $short?date('j-n G:i', $timestamp):date('j-n-Y G:i:s', $timestamp);
  $tmp = explode(' ', $date);

  $date = $tmp[0];
  $time = $tmp[1];

  $tmp = explode('-', $date);

  $months = array(
       1=>'января', 2=>'февраля', 3=>'марта',
       4=>'апреля', 5=>'мая', 6=>'июня',
       7=>'июля', 8=>'августа', 9=>'сентября',
       10=>'октября', 11=>'ноября', 12=>'декабря'
  );

  if($short) {
    $months = array(
         1=>'Янв.', 2=>'Фев.', 3=>'Мар.',
         4=>'Апр.', 5=>'Мая', 6=>'Июн.',
         7=>'Июля', 8=>'Авг.', 9=>'Сен.',
         10=>'Окт.', 11=>'Ноя.', 12=>'Дек.'
    );
  }

  $tmp[1] = $months[$tmp[1]];
  $date = implode(' ', $tmp);
  $date = $short?$date:$date.'г.';

  if($getTime) {
    $time = ' в '.$time;
    $date = $date . $time;
  }

  return $date;
}

/*
 * nice func for test memory
function convert($size)
 {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
 }

print convert(xdebug_peak_memory_usage(true));
*/