<?php
//htmlentities()
// Load classes.
$this->loadClass("button","htmlelements");
// Display a list of users online.
foreach ($users as $user) {
	echo "&nbsp;";
    $objUserPic =& $this->getObject('imageupload', 'useradmin');
    echo "<image src=\"" . $objUserPic->smallUserPicture($user['userId']) . "\"/>";
	echo "&nbsp;";
	$fullName = $user["firstName"] . " " . $user["surname"];
	//$fullName = preg_replace("/'/","&#39;",$fullName);
	echo $fullName;
	echo "&nbsp;";
	$imPopup =& $this->getObject('popup','instantmessaging');
	$imPopup->setup($user['userId'], null, '');
	echo preg_replace("/'/","\'",$imPopup->show()); 
	echo "<br>";
}
?>