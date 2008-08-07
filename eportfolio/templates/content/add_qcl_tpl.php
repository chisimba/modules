<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('textarea','htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$type = 'Affiliation';
	$categorytypeList = $this->objDbCategorytypeList->listCategorytype($type);
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addQualification",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addqclconfirm'
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
	$mydropdown = new dropdown('qcl_type');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$mydropdown->addOption($categories['id'], $categories['type']);
			
			
		}
		
	}else{
		$mydropdown->addOption('None', "-There are no Types-");	
	}
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_qcltype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($mydropdown->show());				
	$objTable->addRow($row, NULL);
    	//qcl title text box
	$textinput = new textinput("title","");
	$textinput->size = 40;
	$form->addRule('title', 'Please enter the title','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_qcltitle",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());				
	$objTable->addRow($row, NULL);

 	//organisation text field	
	$textinput = new textinput("organisation","");
	$textinput->size = 40;
	$form->addRule('organisation', 'Please enter the organisation','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_organisation",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());				
	$objTable->addRow($row, NULL);

    	//qcl level text field	
	$textinput = new textinput("qcl_level","");
	$textinput->size = 40;
	$form->addRule('qcl_level', 'Please enter the qualification level','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_qcllevel",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());				
	$objTable->addRow($row, NULL);
	
	//award date calendar
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_qclawarddate",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('award_date', 'yes', 'no', '');
	$form->addRule('award_date', 'Please enter the award date','required');
	$row = array($startField);
	$objTable->addRow($row, NULL);
 	//short description text field
	$textinput = new textinput("shortdescription","");
	$textinput->size = 40;
	$form->addRule('shortdescription', 'Please enter the short description','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);

 	
    	//Full description text field
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_longdescription",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	//Add the WYSWYG editor
	    $editor = $this->newObject('htmlarea', 'htmlelements');
	    $editor->name = 'longdescription';
	    $editor->height = '300px';
	    $editor->width = '450px';
	    $longdescription = '';
	    $editor->setContent($longdescription);

	$row = array($editor->showFCKEditor());	   
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
                'action'=>'view_qcl'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());
	echo $form->show();
?>
