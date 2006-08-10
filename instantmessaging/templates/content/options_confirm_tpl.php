<?php
	echo($objLanguage->languageText('mod_instantmessaging_optionsupdated','instantmessaging')."<br/>");
    // Display the close window button.
	$icon =& $this->newObject('geticon','htmlelements');
	$icon->setIcon('close');
	$icon->alt=$this->objLanguage->languageText("im_closewindow",'instantmessaging');
    $icon->align = "absmiddle";
	$icon->extra=' onclick="window.close()" ';
	echo $icon->show();
?>
