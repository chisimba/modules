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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editcontact",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editcontactconfirm',
			'id'=>$id
	)));
	$objTable = new htmltable();
	$objTable->width='30';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);
	$row = array($objUser->fullName());
	$objTable->addRow($row, NULL);
	
    	//type text box		
	$textinput = new textinput("contact_type",$contact_type);
	$textinput->size = 30;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);

	//Contact type text box		
	$textinput = new textinput("contactType",$contactType);
	$textinput->size = 30;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contacttype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);

    	//country_code text box
	$textinput = new textinput("country_code",$country_code);
	$textinput->size = 30;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_countrycode",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);
	
 	//area_code text field
	$textinput = new textinput("area_code",$area_code);
	$textinput->size = 30;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_areacode",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);
 	
    	//contact number text field
	$textinput = new textinput("id_number",$id_number);
	$textinput->size = 30;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contactnumber",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
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
