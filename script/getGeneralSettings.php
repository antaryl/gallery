<?php
	include "../config.php";
	$sql="SELECT * FROM config";
	$rs=$conn->query($sql);
	$config = Array();
		while($row = $rs->fetch_assoc()){
			$config[$row['parameter']] = json_decode($row['value'], true);
		}

	$config['album_number'] = getAlbumNumber();
	$config['pictures_number'] = getPicturesNumber();

	if (isset($_POST['response'])){
	  echo json_encode($config);
	}

?>
