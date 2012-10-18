<?php
/**
 * json generator for jquery "Redactor" editor
 * @package redactor
 */

header("Content-Type: application/json;charset=utf-8");

$dir = $_SERVER['DOCUMENT_ROOT'].'/public/uploads/thumbnails/';

$files = new DirectoryIterator($dir);

$images = array();

foreach( $files as $file ) {
  if( $file->isFile() && preg_match('~\.(jpeg|jpg|png|gif|bmp)~i', $file)){
      $images[] = (string)$file;
  }
}
?>
[
<?php
$length = count($images);
for($i=0; $i<$length; $i++) {
?>
 {
  "thumb": "/public/uploads/thumbnails/<?php echo $images[$i];?>",
  "image": "/public/uploads/<?php echo $images[$i]; echo '?v='.time(); ?>"
 }<?php echo ($i+1!=$length)?',':'';?><?php echo PHP_EOL;?>

<?php
}
?>
]