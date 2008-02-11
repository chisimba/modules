<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	//	$objLabel =& $this->newObject('label', 'htmlelements');
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_adddemographics",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'adddemographicsconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>",
	$objUser->fullName());
	$objTable->addRow($row, 'odd');
	
    	//demographics_type text box		
	$textinput = new textinput("demographics_type","");
	$textinput->size = 70;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_demographicstype",'eportfolio').":</b>",
	$textinput->show());
		
    	//birth text box
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('birth', 'yes', 'no', '');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_birth",'eportfolio').":</b>", $startField);
	
 	//nationality text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("nationality","");
	$textinput->size = 70;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_nationality",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');
	
    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array( "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_contact',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
