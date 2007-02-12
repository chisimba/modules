<?php

	/**
	* Template to display when user tries to chat but is not in a room
	*
	*/
	
	
	$this->objHeading->type = 2;
	$this->objHeading->cssClass = 'error';
	
	$this->objHeading->str = $this->objLanguage->languageText('phrase_notinroom');
	echo $this->objHeading->show();


?>
