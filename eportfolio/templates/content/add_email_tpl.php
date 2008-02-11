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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addemail",'eportfolio');
	echo $objHeading->show();
/*
        if ($this->getParam('email') == '') {
            $messages[] = $this->objLanguage->languageText('mod_eportfolio_enteremailaddress', 'eportfolio');
        } else if (!$this->objUrl->isValidFormedEmailAddress($this->getParam('useradmin_email'))) {
            $messages[] = $this->objLanguage->languageText('mod_eportfolio_entervalidemailaddress', 'eportfolio');
        }
    
	if (count($messages) > 0) {
	    echo '<ul><li><span class="error">'.$this->objLanguage->languageText('mod_eportfolio_infonotsavedduetoerrors', 'eportfolio').'</span>';
	    
	    echo '<ul>';
	        foreach ($messages as $message)
	        {
	            echo '<li class="error">'.$message.'</li>';
	        }
	    echo '</ul></li></ul>';
	}
*/	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addemailconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>",
	$objUser->fullName());
	$objTable->addRow($row, 'odd');
	
    	//email_type text box		
	$textinput = new textinput("email_type","");
	$textinput->size = 70;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_emailtype",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');	
 	
	// Spacer
	$objTable->startRow();
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	    $objTable->addCell('&nbsp;');
	$objTable->endRow();

	//email text field
	$textinput = new textinput("email","");
	$textinput->size = 70;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_email",'eportfolio').":</b>",
	$textinput->show());
	$objTable->addRow($row, 'even');

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
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array( "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_contact',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
