<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$type = 'Place';
	$categorytypeList = $this->objDbCategorytypeList->listCategorytype($type);
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
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);
	$row = array($objUser->fullName());
	$objTable->addRow($row, NULL);
	
    	//type text box	
	$dropdown = new dropdown('address_type');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$dropdown->addOption($categories['id'], $categories['type']);
			$dropdown->setSelected($address_type);
		}
		
	}else{
		$dropdown->addOption('None', "-There are no categories-");	
	}
	
	$row = array($dropdown->show());
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_type",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($dropdown->show());
	$objTable->addRow($row, NULL);
		
    	//street_no text box
	$street_no = new textinput("street_no",$street_no);
	$street_no->size = 25;
	$form->addRule('street_no','Please enter the Street No','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetno",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($street_no->show());
	$objTable->addRow($row, NULL);

 	//street_name text field
	$street_name = new textinput("street_name",$street_name);
	$street_name->size = 25;
	$form->addRule('street_name','Please enter the Street Name','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_streetname",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($street_name->show());
	$objTable->addRow($row, NULL);

    	//locality text field
	$locality = new textinput("locality",$locality);
	$locality->size = 25;
	$form->addRule('locality','Please enter the Locality','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_locality",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($locality->show());
	$objTable->addRow($row, NULL);

	//city text field
	$city = new textinput("city",$city);
	$city->size = 25;
	$form->addRule('city','Please enter the City','required');
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_city",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);		
	$row=array($city->show());
	$objTable->addRow($row, NULL);

		
 	//postcode select box
	$postcode = new textinput("postcode",$postcode);
	$postcode->size = 25;
	$form->addRule('postcode','Please enter the Post Code','required');
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_postcode",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);		
	$row=array($postcode->show());
	$objTable->addRow($row, NULL);


 	//postal_address text box
	$postal_address = new textinput("postal_address",$postal_address);
	$postal_address->size = 25;
	$form->addRule('postal_address','Please enter the Post Address','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_postaddress",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($postal_address->show());
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
