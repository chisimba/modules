<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_edit",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editaddressconfirm',
			'id'=>$id
	)));
	$objTable = new htmltable();
	$objTable->width='30';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>",
	$objUser->fullName());
	$objTable->addRow($row, 'odd');
	
    	//type text box		
	$textinput = new textinput("address_type",$address_type);
	$textinput->size = 40;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_type",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');		
    	//street_no text box
	$textinput = new textinput("street_no",$street_no);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetno",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');	
 	//street_name text field
	$textinput = new textinput("street_name",$street_name);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetname",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even'); 	
    	//locality text field
	$textinput = new textinput("locality",$locality);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_locality",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');	
	//city text field
	$textinput = new textinput("city",$city);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_city",'eportfolio').":</b>",
	$textinput->show());			
	$objTable->addRow($row, 'even');
		
 	//postcode select box
	$textinput = new textinput("postcode",$postcode);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_postcode",'eportfolio').":</b>",
	$textinput->show());			
	$objTable->addRow($row, 'even');

 	//postal_address text box
	$textinput = new textinput("postal_address",$postal_address);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_postaddress",'eportfolio').":</b>",
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
