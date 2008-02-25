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
	$modetype = 'Mode';
	$modetypeList = $this->objDbCategorytypeList->listCategorytype($modetype);
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
	
    	//type drop down list	
	$dropdown = new dropdown('contact_type');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$dropdown->addOption($categories['id'], $categories['type']);
			$dropdown->setSelected($contact_type);
		}
		
	}else{
		$dropdown->addOption('None', "-There are no types-");	
	}
	
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($dropdown->show());
	$objTable->addRow($row, NULL);

	//Contact type dropdown	
	$mydropdown = new dropdown('contactType');
	
	if (!empty($modetypeList))
	{
		foreach ($modetypeList as $categories)
		{

			$mydropdown->addOption($categories['id'], $categories['type']);
			$mydropdown->setSelected($contactType);
		}
		
	}else{
		$mydropdown->addOption('None', "-There are no modes-");	
	}
	
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contacttype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($mydropdown->show());
	$objTable->addRow($row, NULL);

    	//country_code text box
	$country_code = new textinput("country_code",$country_code);
	$country_code->size = 30;
	$form->addRule('country_code','Please enter the country code','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_countrycode",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($country_code->show());
	$objTable->addRow($row, NULL);
	
 	//area_code text field
	$area_code = new textinput("area_code",$area_code);
	$area_code->size = 30;
	$form->addRule('area_code','Please enter the area code','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_areacode",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($area_code->show());
	$objTable->addRow($row, NULL);
 	
    	//contact number text field
	$id_number = new textinput("id_number",$id_number);
	$id_number->size = 30;
	$form->addRule('id_number','Please enter the Id number','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contactnumber",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($id_number->show());
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
