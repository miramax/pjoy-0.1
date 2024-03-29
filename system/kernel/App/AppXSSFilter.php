<?php
/**
* This file may be part of Kohana Framework XSS classes
* Pjoy Framework v0.8
* An open source application development framework for PHP 5.3 or newer
* @copyright	CopyLeft (cl) - 2012, no license
* @license no license, Public Domain
*/


class AppXSSFilter
{

  public  $clean = 'all';

  public function filter() {
    $this->clean  = trim(strtoupper($this->clean));
    $data = array(
    'GET'    =>&$_GET,
    'POST'   =>&$_POST,
    'COOKIE' =>&$_COOKIE,
    'FILES'  =>&$_FILES
    );

    if($this->clean === 'ALL' || $this->clean === '*')
    {
      $this->clean = 'GET,POST,COOKIE,FILES';
    }
    $dataForClean = explode(',',$this->clean);
    if(count($dataForClean))
    {
       foreach ($dataForClean as $key => $value)
       {
          if(isset ($data[$value]) && count($data[$value]))
          {
             $this->doXssClean($data[$value]);
          }
       }
   }
  return true;
 }


  private function doXssClean(&$data) {
       if(is_array($data) && count($data))
    {
      foreach($data as $k => $v)
    {
       $data[$k] = $this->doXssClean($v);
    }
       return $data;
  }

  if(trim($data) === '')
  {
  return $data;
  }

  // xss_clean function from Kohana framework 2.3.1
  $data = str_replace(array('&amp;amp;','&amp;lt;','&amp;gt;'), array('&amp;amp;amp;','&amp;amp;lt;','&amp;amp;gt;'), $data);
  $data = preg_replace('/(&amp;#*\w+)[\x00-\x20]+;/u', '$1;', $data);
  $data = preg_replace('/(&amp;#x*[0-9A-F]+);*/iu', '$1;', $data);
  $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
  // Remove any attribute starting with "on" or xmlns
  $data = preg_replace('#(&lt;[^&gt;]+?[\x00-\x20"\'])(?:on|xmlns)[^&gt;]*+&gt;#iu', '$1&gt;', $data);
  // Remove javascript: and vbscript: protocols
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript…', $data);
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript…', $data);
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding…', $data);
  // Only works in IE: &lt;span style="width: expression(alert('Ping!'));"&gt;&lt;/span&gt;
  $data = preg_replace('#(&lt;[^&gt;]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^&gt;]*+&gt;#i', '$1&gt;', $data);
  $data = preg_replace('#(&lt;[^&gt;]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^&gt;]*+&gt;#i', '$1&gt;', $data);
  $data = preg_replace('#(&lt;[^&gt;]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^&gt;]*+&gt;#iu', '$1&gt;', $data);
  // Remove namespaced elements (we do not need them)
  $data = preg_replace('#&lt;/*\w+:\w[^&gt;]*+&gt;#i', '', $data);
  do
  {
  // Remove really unwanted tags
  $old_data = $data;
  $data = preg_replace('#&lt;/*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^&gt;]*+&gt;#i', '', $data);
  }
  while ($old_data !== $data);
  return $data;
  }

}