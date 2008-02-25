<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objWashout = $this->getObject('washout', 'utilities');
	$objHeading->type=1;
    $objHeading->str =$objUser->getSurname ().$objLanguage->languageText("mod_eportfolio_assertionDisplay", 'eportfolio');
	echo $objHeading->show();
	
	//display user's names

	echo '<br></br>';
	echo '<br></br>';
	echo $objWashout->parseText("<b>".$objLanguage->languageText("mod_eportfolio_lecturer", 'eportfolio').":</b>");
	echo '<br></br>';
	echo '<br></br>';
	echo $objWashout->parseText($objUser->fullName($myinstructor));	
	echo '<br></br>';
	echo '<br></br>';


	//display type
	echo $objWashout->parseText("<b>".$label = $objLanguage->languageText("mod_eportfolio_rationaleTitle",'eportfolio').":</b>");	
	echo '<br></br>';
	echo '<br></br>';

	echo $objWashout->parseText($myrationale);	
	echo '<br></br>';
	echo '<br></br>';


	//display date calendar
	echo $objWashout->parseText("<b>".$label = $objLanguage->languageText("mod_eportfolio_creationDate",'eportfolio').":</b>");
	echo '<br></br>';
	echo '<br></br>';

	echo $objWashout->parseText($this->objDate->formatDate($mycreation_date));
	echo '<br></br>';
	echo '<br></br>';


 	//display short description
	echo $objWashout->parseText("<b>".$label = $objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio').":</b>");
	echo '<br></br>';
	echo '<br></br>';
	echo $objWashout->parseText($myshortdescription);	
	echo '<br></br>';
	echo '<br></br>';

	//display Full description	
	echo $objWashout->parseText("<b>".$label = $objLanguage->languageText("mod_eportfolio_longdescription",'eportfolio').":</b>");
	echo '<br></br>';
	echo '<br></br>';

	echo $objWashout->parseText($mylongdescription);
	echo '<br></br>';
	echo '<br></br>';
	echo "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_assertion',)). "\">".
	$objLanguage->languageText("word_back") . "</a>";	//word_cancel

?>
