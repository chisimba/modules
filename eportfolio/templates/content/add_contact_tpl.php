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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addcontact",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addcontactconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>",
	$objUser->fullName());
	$objTable->addRow($row, 'odd');
	
    	//type text box		
	$textinput = new textinput("contact_type","");
	$textinput->size = 70;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contype",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');

    	//contact_type text box		
	$textinput = new textinput("contactType","");
	$textinput->size = 70;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contacttype",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');
	
    	//country_code text box	
	$textinput = new textinput("country_code","");
	$textinput->size = 70;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_countrycode",'eportfolio').":</b>",
	$textinput->show());
	
 	//area_code text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("area_code","");
	$textinput->size = 70;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_areacode",'eportfolio').":</b>",
	$textinput->show());

 	
    	//contact number text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("id_number","");
	$textinput->size = 70;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contactnumber",'eportfolio').":</b>",
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
