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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editemail",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editemailconfirm',
			'id'=>$id
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);
	$row = array($objUser->fullName());
	$objTable->addRow($row, NULL);
	
    	//email_type text box		
	$textinput = new textinput("email_type",$email_type);
	$textinput->size = 40;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_emailtype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);

 	
	// Spacer
	$objTable->startRow();
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	$objTable->endRow();

	//email text field
	$textinput = new textinput("email",$email);
	$textinput->size = 40;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_email",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);		
	$row=array($textinput->show());
	$objTable->addRow($row, NULL);

	// Spacer
	$objTable->startRow();
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	$objTable->endRow();
	
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
