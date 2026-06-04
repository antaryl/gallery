<?php
include "../config.php";
$res = Array();
$language['language'] = $_POST['language'];
$theme['theme'] = $_POST['theme'];
$admin_private['always_show'] = $_POST['admin_private'];
$admin_show_album_settings['active'] = $_POST['admin_show_album_settings'];
$admin_show_picture_settings['active'] = $_POST['admin_show_picture_settings'];
$admin_show_big_title['active'] = $_POST['admin_show_big_title'];

$language = json_encode($language);
$theme = json_encode($theme);
$admin_private = json_encode($admin_private);
$admin_show_album_settings = json_encode($admin_show_album_settings);
$admin_show_picture_settings = json_encode($admin_show_picture_settings);
$admin_show_big_title = json_encode($admin_show_big_title);

$sql[] = "UPDATE `gallery`.`config` SET `value` = '$language' WHERE `parameter` = 'language'";
$sql[] = "UPDATE `gallery`.`config` SET `value` = '$theme' WHERE `parameter` = 'theme'";
$sql[] = "UPDATE `gallery`.`config` SET `value` = '$admin_private' WHERE `parameter` = 'admin_private'";
$sql[] = "UPDATE `gallery`.`config` SET `value` = '$admin_show_album_settings' WHERE `parameter` = 'admin_show_album_settings'";
$sql[] = "UPDATE `gallery`.`config` SET `value` = '$admin_show_picture_settings' WHERE `parameter` = 'admin_show_picture_settings'";
$sql[] = "UPDATE `gallery`.`config` SET `value` = '$admin_show_big_title' WHERE `parameter` = 'admin_show_big_title'";

$query_number = count($sql);
for ($i = 0; $i <= $query_number; $i++) {
    $rs=$conn->query($sql[$i]);
}

$res['status'] = "OK";
echo json_encode($res);
?>
