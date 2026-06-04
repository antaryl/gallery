<?php
include "../config.php";
$id_picture = $_POST['id_picture'];
$sql="SELECT * FROM pictures LEFT JOIN album ON pictures.id_album = album.id WHERE id_picture='" . $id_picture . "'";
$rs=$conn->query($sql);
$row = $rs->fetch_assoc();
$path = str_replace(" ", "\ ", $row['path']);
$folder_name = str_replace(" ", "\ ", $row['folder_name']);
$picture_name = $row['picture_name'];
$cover_image = $row['cover_image'];
//$row['exif'] = exif_read_data($_SERVER['DOCUMENT_ROOT'] . $path . $folder_name . "/" . $picture_name, 0, true);
echo json_encode($row);
?>
