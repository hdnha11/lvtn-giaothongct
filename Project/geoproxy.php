<?php
	$url = $_GET["url"];
	$res = file_get_contents($url);
	//header("Content-Type: text/html");
	echo $res;
?>
