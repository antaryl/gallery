<?php
set_time_limit(0);
include "../config.php";
include "functions_boot.php";
$files = Array();
$res = Array();
$new_album_data = $_POST['new_album_data'];
$name = $new_album_data['new_album_name'];
$path = $_POST['album_root_folder'];
$folder_name = substr($new_album_data['new_album_path'], 0, strpos($new_album_data['new_album_path'], "/"));
$cover_image = $new_album_data['new_album_cover_image'];
$start_date = $new_album_data['new_album_start_date'];
$end_date = $new_album_data['new_album_end_date'];
$album_private = $new_album_data['new_album_album_private'];
$extensions = array(".jpg",".png",".gif",".JPG",".PNG",".GIF");
$exclusion = array(".","..","Mirko","Rekha","thumbs","Video","Foto");

$sql="INSERT INTO gallery.album (`id`, `name`, `start_date`, `end_date`, `cover_image`, `path`, `folder_name`, `album_private`, `album_visible`) VALUES
(NULL, '$name', '$start_date', '$end_date', '$cover_image', '$path', '$folder_name', '$album_private', '1');";
$rs=$conn->query($sql);

$src_folder = $_SERVER['DOCUMENT_ROOT'] . $path . $folder_name;
$src_files  = scandir($src_folder);
$thumb_width = '150';

check_thumbs($src_folder);

foreach($src_files as $file) {
	$ext = strrchr($file, '.');
	$thumb = $src_folder.'/thumbs/'.$file;
	if (!file_exists($thumb) ) {
		if (in_array($ext, $extensions)){
			make_thumb($src_folder,$file,$thumb,$thumb_width);
		}
	}
}

$last_id = $conn->insert_id;
$complete_path = "../.." . $path . $folder_name;
$dir_handle = opendir($complete_path);
$id_album_get = $last_id;

$img = Array();
while($file = readdir($dir_handle)){
	$img[$file] = $file;
}

foreach($img as $key => $value){
	if ($key != "." && $key != ".." && $key != "Mirko" && $key != "Rekha" && $key != "thumbs" && $key != "Video" && $key != "Foto"){
		if (substr($key, -3) == "JPG" || substr($key, -3) == "jpg" || substr($key, -3) == "JPEG" || substr($key, -3) == "jpeg" || substr($key, -3) == "PNG" ||substr($key, -3) == "png"){
			$sql = "INSERT INTO `gallery`.`pictures` (`id_picture`, `id_album`, `picture_name`, `private`, `private_suffix`, `visible`) VALUES (NULL, '$id_album_get', '$key', '0', '', '1');";
			$rs=$conn->query($sql);
		}
	}
}

$sql = "UPDATE `gallery`.`pictures` SET `private` = '1' WHERE `id_album` = '$id_album_get' AND `picture_name` LIKE '%ADULT%'";
$rs=$conn->query($sql);
$res['status'] = "OK";

echo json_encode($res);

?>
