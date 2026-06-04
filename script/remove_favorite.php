<?php
include "../config.php";
$res = Array();
$id_picture = $_POST['id_picture'];
$sql = "UPDATE `gallery`.`pictures` SET `favorites` = '0' WHERE `id_picture` = '$id_picture'";
$rs=$conn->query($sql);
$res['status'] = "OK";
echo json_encode($res);
?>
