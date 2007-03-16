<?php
	$location = "http://". $_SERVER['HTTP_HOST']."/chisimba_modules/realtime/resources/voice/";
	header("Location: ".$location."redirect.php?var=".$hastoken);
?>
