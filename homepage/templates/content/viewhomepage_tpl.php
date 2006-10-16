<?php
	//echo "<h1>Homepage for ".$objUser->fullname($userId)."</h3>";
	echo "<h1>" . $objLanguage->languageText('mod_homepage_heading', 'homepage') /*. " " . $this->objUser->fullName($userId)*/ . "</h1>";
	if (!$exists) {
	    echo $this->objLanguage->languageText('mod_homepage_nopage', 'homepage');
	}
	else {
		echo $contents;
	}
?>