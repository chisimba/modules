<?php

require_once("imap_class_inc.php");
try {
	$m = new imap;
	$m->setconn("itsnw.uwc.ac.za","fsiu","fsiu");
	$heads = $m->getHeaders();
	$nummails = $m->numMails();
	$thebox = $m->checkMbox();
	//var_dump($thebox);
	$theheads = $m->getHeaderInfo(21);
	var_dump($theheads);
	$themess = $m->getMessage(21);
	//header('Content-type: image/jpg');
	//echo base64_decode($themess[1][0]['filedata']);
	//echo "<h1>$nummails</h1>";
}
catch (Exception $e)
{
	echo $e;
	die();
}
//var_dump($m);