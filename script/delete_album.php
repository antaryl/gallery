<?php
include "../config.php";
$res = Array();
$album_id = $_POST['album_id'];


$sql = "DELETE FROM album WHERE id='$album_id'";
$rs=$conn->query($sql);

$sql = "DELETE FROM pictures WHERE id_album='$album_id'";
$rs=$conn->query($sql);

$res['status'] = "OK";
echo json_encode($res);
?>
