<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$usercontexts = $this->getUserContexts();
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editactivity",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editactivityconfirm',
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
    	//contexttitle text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contexttitle",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	//Context Drop down list
	$dropdown = new dropdown('contexttitle');
	if (!empty($usercontexts))
	{
		$dropdown->addOption('None', "-Select Option-");
		foreach ($usercontexts as $mycontext)
		{
			$dropdown->addOption($mycontext['contextcode'], $mycontext['title']);
			$dropdown->setSelected($contexttitle);
		}
		
	}else{
		$dropdown->addOption('None', "You are not registered for any course");	
	}
	
	$row = array($dropdown->show());	
	$objTable->addRow($row, NULL);
	//end Context drop down list    	
	
	//type text box		
	$textinput = new textinput("activityType",$activityType);
	$textinput->size = 60;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitytype",'eportfolio').":</b>");	
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);
	//activity start text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('activityStart', 'yes', 'no', $activityStart);
	$row = array($startField);
	$objTable->addRow($row, NULL);
    	//activity finish text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activityfinish",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('activityFinish', 'yes', 'no', $activityFinish);
	$row = array($startField);
	$objTable->addRow($row, NULL);
 	//short description text field
	$textinput = new textarea("shortdescription",$shortdescription);
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
                'action'=>'view_activity'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());
	echo $form->show();
?>
