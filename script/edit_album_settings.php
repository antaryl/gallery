<?php
include "../config.php";
$res = Array();
$id = $_POST['album_id'];
$name = $_POST['name'];
$cover_image = $_POST['cover_image'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$album_private = $_POST['album_private'];
$album_only_private = $_POST['album_only_private'];

$sql = "UPDATE `gallery`.`album` SET `name` = '$name',`cover_image` = '$cover_image',`start_date` = '$start_date',`end_date` = '$end_date', `album_private` = '$album_private', `show_only_private` = '$album_only_private'  WHERE `id` = '$id'";
$rs=$conn->query($sql);
$res['status'] = "OK";
echo json_encode($res);
?>
