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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addAddress",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addaddressconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);	
	$row = array($objUser->fullName());
	$objTable->addRow($row, NULL);
	
    	//Type text box		
	$textinput = new textinput("address_type","");
	$textinput->size = 25;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_type",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);
		
    	//Street No text box
	$textinput = new textinput("street_no","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetno",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);
	
 	//Street name text field
	$textinput = new textinput("street_name","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetname",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);
 	
    	//Locality text field
	$textinput = new textinput("locality","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_locality",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);

	
	//City text field
	$textinput = new textinput("city","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_city",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);
			
 	//Post Code select box
	$textinput = new textinput("postcode","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_postcode",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);

 	//Postal Address text box
	$textinput = new textinput("postal_address","");
	$textinput->size = 25;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_postaddress",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());
	$objTable->addRow($row, NULL);

	
    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();

        // Show the cancel link
        $buttonCancel = new button("submit",
        $objLanguage->languageText("word_cancel"));
        $objCancel =& $this->getObject("link","htmlelements");
        $objCancel->link($this->uri(array(
                    'module'=>'eportfolio',
                'action'=>'view_contact'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());

	echo $form->show();	
?>
