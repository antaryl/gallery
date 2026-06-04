<?php
include "../config.php";
$res = Array();
$picture_id = $_POST['picture_id'];
$private = $_POST['private'];
$visible = $_POST['visible'];
$favorite = $_POST['favorite'];
$cover_image_check = $_POST['cover_image'];

$sql = "UPDATE `gallery`.`pictures` SET `private` = '$private',`visible` = '$visible',`favorites` = '$favorite' WHERE `id_picture` = '$picture_id'";
$rs=$conn->query($sql);

$sql="SELECT * FROM `gallery`.`pictures` LEFT JOIN `gallery`.`album` ON pictures.id_album = album.id  WHERE `id_picture` = '$picture_id'";
$rs=$conn->query($sql);
$row = $rs->fetch_assoc();
$album_id = $row['id'];
$picture_name = $row['picture_name'];
$picture_name_no_ext = substr($picture_name, 0, -4);
$picture_ext = substr($picture_name, -4);
$path = $row['path'];
$folder_name = $row['folder_name'];


if ($private == '1' && strpos($picture_name, '_ADULT') == false){
  rename ($_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/" . $picture_name, $_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/" . $picture_name_no_ext . "_ADULT" . $picture_ext);
  rename ($_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/thumbs/" . $picture_name, $_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/thumbs/" . $picture_name_no_ext . "_ADULT" . $picture_ext);
  $sql = "UPDATE `gallery`.`pictures` SET `picture_name` = '".$picture_name_no_ext."_ADULT".$picture_ext."' WHERE `id_picture` = '$picture_id'";
  $rs=$conn->query($sql);
}elseif($private == '0'){
  $picture_name_no_priv = str_replace('_ADULT', '', $picture_name) ;
  rename ($_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/" . $picture_name, $_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/" . $picture_name_no_priv);
  rename ($_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/thumbs/" . $picture_name, $_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/thumbs/" . $picture_name_no_priv);
  $sql = "UPDATE `gallery`.`pictures` SET `picture_name` = '$picture_name_no_priv' WHERE `id_picture` = '$picture_id'";
  $rs=$conn->query($sql);
}

if ($cover_image_check == 1){
  $sql = "UPDATE `gallery`.`album` SET `cover_image` = '$picture_name' WHERE `id` = '$album_id'";
  $rs=$conn->query($sql);
}


$res['status'] = "OK";
echo json_encode($res);
?>
