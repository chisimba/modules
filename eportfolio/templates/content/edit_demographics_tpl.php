<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$type = 'Demographics';
	$categorytypeList = $this->objDbCategorytypeList->listCategorytype($type);
	$objPopupcal = $this->newObject('datepickajax', 'popupcalendar');
//	$objLabel =& $this->newObject('label', 'htmlelements');
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editdemographics",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editdemographicsconfirm',
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
	$dropdown = new dropdown('demographics_type');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$dropdown->addOption($categories['id'], $categories['type']);
			$dropdown->setSelected($demographics_type);
		}
		
	}else{
		$dropdown->addOption('None', "-There are no Types-");	
	}


	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_demographicstype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($dropdown->show());
	$objTable->addRow($row, NULL);
		
    	//birth text box
	$startField = $this->objPopupcal->show('birth', 'no', 'no', $birth);
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_birth",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);	
	$form->addRule('birth', 'Please enter your birth date','required');		
	$row=array($startField);
	$objTable->addRow($row, NULL); 
	
 	//nationality text field
	$textinput = new textinput("nationality",$nationality);
	$textinput->size = 30;
	$form->addRule('nationality', 'Please enter your nationality','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_nationality",'eportfolio').":</b>");
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
