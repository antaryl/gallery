<?php
include "./script/functions_boot.php";
if (isset($_GET['album'])){
	DrawAlbum($_GET['album']);
	DrawFooter();
}else if(isset($_GET['favorites'])) {
	DrawFavorites();
	DrawFooter();
}else{
	if ($config['admin_show_big_title']['active']){
		DrawTitle();
	}
	DrawAlbumList();
	DrawFooter();
}

function DrawTitle(){
	global $config;
	$config_lang = $config['language']['language'];
	echo "<div id='head_title' class='cartoon_titles'>".$config['lang'][$config_lang]['albums_list']['title']."</div>";
}

function DrawFooter(){
	echo "<div id='footer_container'><hr></div>";
}

function analyzeNewPictures($id_album, $pictures){
	global $conn;
	$to_scan_info = array();

	$src_files_database = array();
	$src_files_scandir = array();
	$src_files_thumbs_scandir= array();

	$exclusion = array(".","..","Mirko","Rekha","thumbs","Video","Foto");
	$extensions = array(".jpg",".png",".gif",".JPG",".PNG",".GIF");
	//set path folder and album id
	foreach ($pictures as $id => $value) {
		$to_scan_info['path'] = $value['path'];
		$to_scan_info['folder_name'] = $value['folder_name'];
		$to_scan_info['id_album'] = $value['id_album'];
	}

	//find all album pictures in database
	$sql="SELECT * FROM pictures WHERE id_album='" . $id_album . "'";
	$rs=$conn->query($sql);
	while($row = $rs->fetch_assoc()){
		$src_files_database[] = $row['picture_name'];
	}

	//find all files in album folder
	$src_folder = $_SERVER['DOCUMENT_ROOT'] . $to_scan_info['path'] . $to_scan_info['folder_name'];
	$src_files  = scandir($src_folder);
	foreach ($src_files as $key => $value) {
		if (!in_array($value, $exclusion)){
			$src_files_scandir[] = $value;
		}
	}

	//find all files in album thumbs folder
	$src_folder_thumbs = $_SERVER['DOCUMENT_ROOT'] . $to_scan_info['path'] . $to_scan_info['folder_name'] . "/thumbs";
	$src_files_thumbs  = scandir($src_folder_thumbs);
	foreach ($src_files_thumbs as $key => $value) {
		if (!in_array($value, $exclusion)){
			$src_files_thumbs_scandir[] = $value;
		}
	}

	//if exist a file in album folder that not exists in thumb folder, generate thumb
	foreach ($src_files_scandir as $key => $value) {
		if (!in_array($value, $src_files_thumbs_scandir)){
			$ext = strrchr($value, '.');
			$dest = $src_folder_thumbs . "/" . $value;
			$thumb_width = '150';
			if (in_array($ext, $extensions)){
				make_thumb($src_folder,$value,$dest,$thumb_width) ;
			}
		}
	}

	//if there is a picture in album folder that not exist in database, write in picture table
	$count = "0";
	foreach ($src_files_scandir as $key => $value) {
		if (!in_array($value, $src_files_database)){
			$sql = "INSERT INTO `gallery`.`pictures` (`id_picture`, `id_album`, `picture_name`, `private`, `private_suffix`, `visible`) VALUES (NULL, '$id_album', '$value', '0', '', '1');";
			$rs=$conn->query($sql);
			$count++;
		}
	}

	// if count is != 0, refresh page
	return $count;
}

function getPicturesFromDatabase($album_id, $search_private=false, $private_only=false){
	global $conn;
	global $config;
	$pictures = Array();
	if ($search_private == true){
		if ($private_only == false){
			$sql="SELECT * FROM pictures LEFT JOIN album ON pictures.id_album = album.id WHERE id_album='" . $album_id . "' ORDER BY favorites DESC, picture_name ASC";
		}else{
			$sql="SELECT * FROM pictures LEFT JOIN album ON pictures.id_album = album.id WHERE id_album='" . $album_id . "' AND private=1 ORDER BY favorites DESC, picture_name ASC";
		}
	}else{
		$sql="SELECT * FROM pictures LEFT JOIN album ON pictures.id_album = album.id WHERE id_album='" . $album_id . "' AND private=0 ORDER BY favorites DESC, picture_name ASC";
	}
	$rs=$conn->query($sql);
	while($row = $rs->fetch_assoc()){
		if (in_array(substr($row['picture_name'], -3), $config['permitted_ext'])){
			$pictures[$row['id_picture']]['id_album'] = $row['id_album'];
			$pictures[$row['id_picture']]['id_picture'] = $row['id_picture'];
			$pictures[$row['id_picture']]['picture_name'] = $row['picture_name'];
			$pictures[$row['id_picture']]['private'] = $row['private'];
			$pictures[$row['id_picture']]['visible'] = $row['visible'];
			$pictures[$row['id_picture']]['favorites'] = $row['favorites'];
			$pictures[$row['id_picture']]['path'] = $row['path'];
			$pictures[$row['id_picture']]['folder_name'] = $row['folder_name'];
		}
	}
	return $pictures;
}

function getAlbumFromDatabase($album_id = "0"){
	global $conn;
	$albums = Array();
	if ($album_id == "0"){
		$sql="SELECT * FROM album ORDER BY start_date DESC";
	}else{
		$sql="SELECT * FROM album WHERE id='" .$album_id. "'";
	}
	$rs=$conn->query($sql);
	while($row = $rs->fetch_assoc()){
		$albums[$row['id']]['id'] = $row['id'];
		$albums[$row['id']]['name'] = $row['name'];
		$albums[$row['id']]['start_date'] = date("d/m/Y", strtotime($row['start_date']));
		$albums[$row['id']]['end_date'] = date("d/m/Y", strtotime($row['end_date']));
		$albums[$row['id']]['path'] = $row['path'];
		$albums[$row['id']]['folder_name'] = $row['folder_name'];
		$albums[$row['id']]['album_private'] = $row['album_private'];
		$albums[$row['id']]['cover_image'] = $row['cover_image'];
		$albums[$row['id']]['show_only_private'] = $row['show_only_private'];
	}
	return $albums;
}

function getFavorites(){
	global $conn;
	$sql="SELECT * FROM pictures LEFT JOIN album ON pictures.id_album = album.id WHERE pictures.favorites = '1'";
	$rs=$conn->query($sql);
	while($row = $rs->fetch_assoc()){
		$res[] = $row;
	}
	return $res;
}

function DrawFavorites(){
	global $config;
	$config_lang = $config['language']['language'];
	$favorites = getFavorites();
	echo 		"<div id='container_album_title'>";
	echo 			"<div id='album_title' class='cartoon_titles'>".$config['lang'][$config_lang]['favorites']['title']."</div>";
	echo 		"</div>";
	if (count($favorites) > 0){
		echo "<div class='picture_container_fit'>";
		foreach ($favorites as $key => $value) {
			if ($value['private'] == '1'){
				echo "<div class='picture_container_private'>";
			}else{
				echo "<div class='picture_container'>";
			}
			echo 	"<div class='img_favorite_selected_fav img_small' title='".$config['lang'][$config_lang]['favorites_title']['remove']."' onClick='removeFavorite(\"".$value['id_picture']."\");'></div>";
			echo 	"<a href='".$value['path'] . $value['folder_name'] . "/". $value['picture_name'] ."' data-lightbox='image-1' data-title='".$value['picture_name']."'>";
			echo 		"<img class='picture_in_album' src='".$value['path'] . $value['folder_name'] . "/thumbs/". $value['picture_name'] ."' />";
			echo 	"</a>";
			echo 	"<div class='picture_name'>".$value['name']."<br>".$value['picture_name']."</div>";
			echo "</div>";
		}
		echo "</div>";
	}else{
		echo "<div class='picture_container_fit'>";
		echo $config['lang'][$config_lang]['favorites']['empty_favorites'];
		echo "</div>";
	}
	DrawAlbumMenu();
}

function DrawAlbum($album_id){
	global $conn;
	global $config;
	$config_lang = $config['language']['language'];
	$album = getAlbumFromDatabase($album_id);
	if ($config['admin_private']['always_show']){$album[$album_id]['album_private'] = 1;}
	$pictures = getPicturesFromDatabase($album_id,$album[$album_id]['album_private'],$album[$album_id]['show_only_private']);
	/*if (analyzeNewPictures($album_id, $pictures) != '0'){
		header("Refresh:0");
	}*/
	analyzeNewPictures($album_id, $pictures);
	echo "<div class='picture_container_fit'>";
	echo 		"<div id='container_album_title'>";
	echo 		"<div id='album_title' class='cartoon_titles'>".$album[$album_id]['name']."</div>";
	if ($album[$album_id]['start_date'] != '0000-00-00' && $album[$album_id]['end_date'] != '01/01/1970'){
		echo "<div id='album_date'>".$config['lang'][$config_lang]['album']['from'].": ".$album[$album_id]['start_date']." - ".$config['lang'][$config_lang]['album']['to'].": ";
		echo $album[$album_id]['end_date'] . "</div>";
	}elseif ($album[$album_id]['start_date'] != '01/01/1970' && $album[$album_id]['end_date'] == '01/01/1970'){
		echo "<div id='album_date'>".$album[$album_id]['start_date']."</div>";
	}else{
		echo "<br />";
	}
	echo "<div><text style='font-size:12px'>  ".$config['lang'][$config_lang]['album']['pics_number'].": ".count($pictures)."</font></div>";
	echo "</div>";

	foreach ($pictures as $picture){
		if ($picture['visible'] == '1'){
			if ($picture['private'] == '1'){
				echo "<div class='picture_container_private'>";
			}else{
				echo "<div class='picture_container'>";
			}
			if ($config['admin_show_picture_settings']['active']){
				echo 	"<div class='img_settings img_small' title='".$config['lang'][$config_lang]['album_titles']['picture_settings']."' onClick='PictureSettings(\"" . $picture['id_picture'] . "\");'></div>";
			}
			$class_favorites = "";
			if ($picture['favorites'] == 1){$class_favorites = "img_small img_favorite_selected";}
			echo 	"<div class='$class_favorites' title='".$config['lang'][$config_lang]['favorites']['title']."'></div>";
			echo 	"<a href='".$album[$album_id]['path'] . $album[$album_id]['folder_name'] . "/". $picture['picture_name'] ."' data-lightbox='image-1' data-title='".$picture['picture_name']."'>";
			echo 		"<img class='picture_in_album' src='".$album[$album_id]['path'] . $album[$album_id]['folder_name'] . "/thumbs/". $picture['picture_name'] ."' />";
			echo 	"</a>";
			echo 	"<div class='picture_name'>".$picture['picture_name']."</div>";
			echo "</div>";
		}
	}
	echo "</div>";
	DrawAlbumMenu();
}

function DrawAlbumList(){
	global $config;
	$config_lang = $config['language']['language'];
	echo getAlbumNumber(true);
	$albums = getAlbumFromDatabase();

	foreach ($albums as $album){
		if ($album['start_date'] == "01/01/1970"){
			$album_start_date = "";
		}else{
			$album_start_date = "<br>" . $album['start_date'];
		}
		echo "<div class='picture_container'>";
		if ($config['admin_show_album_settings']['active']){
			echo 	"<div class='img_settings img_small' title='".$config['lang'][$config_lang]['albums_list_titles']['settings']."' onClick='AlbumSettings(\" " . $album['id'] . " \");'></div>";
		}
		echo 	"<a href='gallery.php?album=".$album['id']."'>";
		if ($album['cover_image'] == 'default'){
			echo 		"<img class='picture_in_album' src='".$album['path'] . $album['folder_name'] . "/thumbs/". randomAlbumCover($album['id']) ."' />";
		}else{
			echo 		"<img class='picture_in_album' src='".$album['path'] . $album['folder_name'] . "/thumbs/". $album['cover_image'] ."' />";
		}

		echo 	"</a>";
		if ($config['admin_private']['always_show']){$album['album_private'] = true;}
		if ($album['album_private'] == true){
			$lock = 'unlock';
			$title = $config['lang'][$config_lang]['albums_list_titles']['unlocked'];
		}else{
			$lock = 'lock';
			$title = $config['lang'][$config_lang]['albums_list_titles']['locked'];
		}
		if ($config['admin_private']['always_show'] == true){$lock = 'unlock';}
		echo 	"<div class='picture_name'>".$album['name'] . $album_start_date. "<div class='img_micro img_$lock' id='img_lock' title='".$title."'></div></div>";
		echo "</div>";
	}
	DrawAlbumListMenu();
}
function DrawAlbumListMenu(){
	global $config;
	$config_lang = $config['language']['language'];
	echo "<div id='gallery_config' class='img_medium' title='".$config['lang'][$config_lang]['general_settings_titles']['config']."' onClick='GeneralSettings()'></div>";
	echo "<a href='gallery.php?favorites'><div id='show_favorites' class='img_medium' title='".$config['lang'][$config_lang]['albums_list_titles']['favorites']."'></div></a>";
	echo "<div id='add_new_album' class='img_medium' title='".$config['lang'][$config_lang]['albums_list_titles']['new_album']."' onClick='AddNewAlbum()'></div>";
}

function DrawAlbumMenu(){
	global $config;
	$config_lang = $config['language']['language'];
	echo "<div id='gallery_config' class='img_medium' title='".$config['lang'][$config_lang]['general_settings_titles']['config']."' onClick='GeneralSettings()'></div>";
	echo "<a href='gallery.php?favorites'><div id='show_favorites' class='img_medium' title='".$config['lang'][$config_lang]['albums_list_titles']['favorites']."'></div></a>";
	echo "<a href='gallery.php'><div id='go_home' class='img_medium' title='".$config['lang'][$config_lang]['album_titles']['back']."'></div></a><br>";
}

function randomAlbumCover($album_id, $search_private=false){
	global $conn;
	if ($search_private == true){
		$sql="SELECT * FROM pictures WHERE id_album='".$album_id."' ORDER BY RAND() LIMIT 1";
	}else{
		$sql="SELECT * FROM pictures WHERE id_album='".$album_id."' AND private ='0' ORDER BY RAND() LIMIT 1";
	}
	$rs=$conn->query($sql);
	$row = $rs->fetch_assoc();
	return $row['picture_name'];
}
?>
