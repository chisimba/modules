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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editaffiliation",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editaffiliationconfirm',
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
	$textinput = new textinput("affiliation_type",$affiliation_type);
	$textinput->size = 40;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_affiliationtype", 'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);	

    	//classification text box
	$textinput = new textinput("classification",$classification);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_classification",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);		
 	//role text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("role",$role);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_role",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);	

 	//organisation text box
	$textinput = new textinput("organisation",$organisation);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_organisation",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);	
 	
    	//start text field
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$startField = $this->objPopupcal->show('start', 'yes', 'no', $start);
	$row = array($startField);
	$objTable->addRow($row, NULL);	
	
	//finish text field
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_activityfinish",'eportfolio').":</b>");			
	$objTable->addRow($row, NULL);		
	$startField = $this->objPopupcal->show('finish', 'yes', 'no', $finish);
	$row = array($startField);
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
                'action'=>'view_affiliation'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);	
	$form->addToForm($objTable->show());
	echo $form->show();
?>
