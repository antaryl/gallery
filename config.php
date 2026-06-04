<?php
  include "database.php";
  include "./script/getGeneralSettings.php";
  include "language.php";

  function getAlbumNumber($html = false){
  		global $conn;
  		global $config;
  		$config_lang = $config['language']['language'];
  		$sql="SELECT * FROM album WHERE album_visible=1";
  		$rs=$conn->query($sql);
  		$album_qty = $rs->num_rows;
  		if ($html == false){
  				return $album_qty;
  		}else{
  			return "<div id='album_qty'>".
  			$config['lang'][$config_lang]['albums_list']['album_number'].": ". $album_qty."</div><br>";
  		}
  }

  function getPicturesNumber(){
      global $conn;
      $sql="SELECT * FROM pictures WHERE visible=1";
      $rs=$conn->query($sql);
      $pictures_qty = $rs->num_rows;
      return $pictures_qty;
  }


?>
