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
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>",
	$objUser->fullName());
	$objTable->addRow($row, 'odd');
	
    	//type text box		
	$textinput = new textinput("affiliation_type",$affiliation_type);
	$textinput->size = 40;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_affiliationtype", 'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');	
    	//classification text box
	$textinput = new textinput("classification",$classification);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_classification",'eportfolio').":</b>",
	$textinput->show());
	
 	//role text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("role",$role);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_role",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');

 	//organisation text box
	$textinput = new textinput("organisation",$organisation);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_organisation",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');
 	
    	//start text field
	$textinput = new textinput("start",$start);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>",
	$textinput->show());
	
	//finish text field
	$objTable->addRow($row, 'even');
	$textinput = new textinput("finish",$finish);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->code2Txt("mod_eportfolio_activityfinish",'eportfolio').":</b>",
	$textinput->show());			
	$objTable->addRow($row, 'even');
		
    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array( "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_affiliation',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
