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



class TplRegexp {



  static private $instance = null;



  private function __construct() { }



  public function init() {
    if ( self::$instance === null ) {
         self::$instance = new self;
    }
      return self::$instance;
  }



  public function rules() {

    return array(


        # basics
        'echo_begin' => array( '/{{\s*/' , '<?php echo ' ),

        'echo_end' => array( '/\s*}}/' , ' ?>' ),

        'php_begin' => array( '/{%\s*/' , '<?php ' ),

        'vars' => array('/@(\w*\d*)/i', '$\1'),

        'php_end' => array( '/\s*%}/' , ' ?>' ),

        'if' => array('/<\?php\s+if\s+(.*)\s*\?>/i', '<?php if(\1) {?>'),

        'elseif' => array('/<\?php\s+elseif\s+(.*)\s*\?>/i', '<?php } elseif(\1) {?>'),

        'else' => array('/<\?php\s+else\s*\?>/i', '<?php } else {?>'),

        'foreach_as_begin' => array( '/\s*for\s+(\$\w+)(\s*,\s*(\$\w+))\s+in\s+(\$\w+)\s*/i' , ' foreach(\4 as \1=>\3){' ),

        'foreach_begin' => array( '/for\s+(\$\w+\d*)\s+in\s+(\$\w+\d*)\s*/i' , 'foreach(\2 as \1){' ),

        'endblocks' => array( '/<\?php\s*(endif|endfor)\s*\?>/' , '<?php } ?>' ),


        # functions
        'range' => array('/for\s+(\$\w+\d*)\s+in\s+range\s*\(\s*(\$?\w*\d*)\s*,\s*(\$?\w*\d*)\s*\)/i', 'for(\1=\2;\1<\3;\1++){'),

        'escape' => array('/(\$.*)\s*\|\s*escape/i', ' htmlspecialchars(\1)'),

        'shortime' => array('/(\$.*)\s*\|\s*shortDate/i', ' PjoyDate(\1, true)'),

        'fulltime' => array('/(\$.*)\s*\|\s*fullDate/i', ' PjoyDate(\1)'),

        'isset' => array('/(\$\_?\w+\d*\.*)\s*\?\s+/i', ' isset(\1)?\1:""'),


        # arrays
        'array_keys' => array( '/(\$\w+\d*)(\.(\w*\d*))/i' , '\1["\3"]' ),

        'array_keys_keys2' => array('/(\[".*"\])(\.(\w*\d*))(\.(\w*\d*))/i', '\1["\3"]["\5"]'),

        'array_keys_keys1' => array('/(\[".*"\])(\.(\w*\d*))/i', '\1["\3"]'),

        'comment' => array('/\/\*(.*)/i', ''),

        'concat' => array('/\~\~\~/', '.'),
    );
  }



}

/**
 *  @see Template examples:
 *
 *
 *  {% for @var in @vars %} // foreach($vars as $var)
 *    {{ @var.array.key }}
 *  {% endfor %}
 *
 *
 *
 *  {{ @var? }} // echo isset($var)?$var:''
 *
 *
 *
 *  {% loop @i=0, @i<@var|length, @i++ %}  //for($i=0;$i<count($var);$i++)
 *    {{ @i++ }}
 *  {% endloop %}
 *
 *
 *
 *  {% @var = 1 %}
 *
 *
 *
 *  {% if (@var == 1) && (@var?) %}
 *      {{ @var }}
 *  {% elseif (@var == false) %}
 *      we have some error here!
 *  {% else %}
 *      all is ok
 *  {% endif %}
 *
 *  {{ @datetime|shorttime }} // <?php echo time(datetime, true, false) ;?>
 *
 *
 *
 *  foreach($users as $u){
 *      if($content['createdBy'] == $u['id']) print $u['name'];
 *  }
 *
 *  {% for @u in @users %}
 *      {% if @content.createdBy == @u.id %}
 *        {{ @u.name }}
 *      {% endif %}
 *  {% endfor %}
 *
 *  {% for @i in range(1, @var.count) %}
 *
 *  {% endfor %}
 *
 */