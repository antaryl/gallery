<?php
$conn = mysqli_connect("localhost","*******","*******","gallery");
$rs="";
if ($conn->connect_error) {
  trigger_error('Connessione al database fallita. Errore: '  . $conn->connect_error, E_USER_ERROR);
}

if($rs === false) {
  trigger_error('Query errata: ' . $sql . ' Errore: ' . $conn->error, E_USER_ERROR);
} else {
  $rows_returned = $rs->num_rows;
}


?>
