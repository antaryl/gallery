<?php
include "../config.php";
$sql="SELECT * FROM config WHERE parameter='language'";
$rs=$conn->query($sql);
$row = $rs->fetch_assoc();
$selected_language = json_decode($row['value'], true);
$config_lang = Array();
foreach ($config['lang'] as $key => $value) {
  if ($key == $selected_language['language']){
    $config_lang[] = $value;
  }
}

echo json_encode($config_lang);
?>
