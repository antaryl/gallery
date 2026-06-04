<?php
include "../config.php";
$album_id = $_POST['album_id'];
$sql="SELECT * FROM album WHERE id='" . $album_id . "'";
$rs=$conn->query($sql);
$row = $rs->fetch_assoc();

$sql="SELECT * FROM pictures WHERE id_album='" . $album_id . "'";
$rs=$conn->query($sql);
$pictures = Array();
while($row2 = $rs->fetch_assoc()){
  if ($row2['private'] == true){
    $pictures['private'][] = $row2['picture_name'];
  }else{
    $pictures['public'][] = $row2['picture_name'];
  }
}
$row['picture_number'] = count($pictures['public']) + count($pictures['private']);
$row['picture_public_number'] = count($pictures['public']);
$row['picture_private_number'] = count($pictures['private']);
echo json_encode($row);
?>
