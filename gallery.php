<?php
session_start();
//include ("../menuLaterale.php");
if (isset($_SESSION['username'])) {
?>
<html>
	<head>
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
		<meta http-equiv="Pragma" content="no-cache" />
		<meta http-equiv="Expires" content="0" />
		<title>Gallery</title>
		<link rel="stylesheet" type="text/css" href="./themes/common.css" />
		<?php include "themes.php"; ?>
		<script type="text/javascript" src="./plugin/jQuery.js"></script>
		<script type="text/javascript" src="./plugin/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="./plugin/jquery-ui.css" />
		<link rel="shortcut icon" type="image/png" href="./img/favicon.png"/>
		<link rel="stylesheet" type="text/css" href="./plugin/lightbox/css/lightbox.css" />
		<script type="text/javascript" src="./js/functions.js"></script>
	</head>
	<body>
		<div id='container'>
			<?php include "core.php"; ?>
		</div>
		<div id="dialog"></div>
		<div id="loading"><img src='./img/loading.gif' /></div>
		<script type="text/javascript" src="./plugin/lightbox/js/lightbox.js"></script>
	</body>
</html>
<?php
} else {
    header('Refresh: 2; URL = ../index.php');
 }
?>
