<?php
function check_thumbs($src_folder){
  if (!is_dir($src_folder.'/thumbs')) {
    mkdir($src_folder.'/thumbs');
    chmod($src_folder.'/thumbs', 0777);
  }
}

function make_thumb($folder,$src,$dest,$thumb_width) {
  $source_image = @imagecreatefromjpeg($folder.'/'.$src);
  $width = imagesx($source_image);
  $height = imagesy($source_image);
  $thumb_height = floor($height*($thumb_width/$width));
  $virtual_image = imagecreatetruecolor($thumb_width,$thumb_height);
  imagecopyresampled($virtual_image,$source_image,0,0,0,0,$thumb_width,$thumb_height,$width,$height);
  imagejpeg($virtual_image,$dest,100);
}
?>
