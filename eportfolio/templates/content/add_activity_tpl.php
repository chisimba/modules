<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$type = 'Place';
	$categorytypeList = $this->objDbCategorytypeList->listCategorytype($type);
//	$objLabel =& $this->newObject('label', 'htmlelements');
	$usercontexts = $this->getUserContexts();
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addactivity",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addactivityconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='40';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, 'odd');
	$row = array($objUser->fullName());
	$objTable->addRow($row, NULL);
    	//contexttitle drop down field
	
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contexttitle",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	//Context Drop down list
	$dropdown = new dropdown('contexttitle');
	
	if (!empty($usercontexts))
	{
		$dropdown->addOption('None', "-Select Option-");
		foreach ($usercontexts as $mycontext)
		{
			$dropdown->addOption($mycontext['contextcode'], $mycontext['title']);

		}
	
		
	}else{
		
		$dropdown->addOption('None', "You are not registered for any course");	
	}
		
	$row=array($dropdown->show());
	$objTable->addRow($row, NULL);
	//end Context drop down list

    	//type drop down list	
	$mydropdown = new dropdown('activityType');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$mydropdown->addOption($categories['id'], $categories['type']);
			
		}
		
	}else{
		$mydropdown->addOption('None', "-There are no Types-");	
	}


	
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitytype",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row=array($mydropdown->show());
	$objTable->addRow($row, NULL);
    	//activity start text box	
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('activityStart', 'yes', 'no', '');
	$form->addRule('activityStart', 'Please enter Activity Start Date','required');
	$row = array($startField);
	$objTable->addRow($row, NULL);
    	//activity finish text box	
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activityfinish",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('activityFinish', 'yes', 'no', '');
	$form->addRule('activityFinish', 'Please enter Activity Finish Date','required');
	$row = array($startField);
	$objTable->addRow($row, NULL);
    	//short description text box
	$shortdescription = new textarea("shortdescription","");
	$form->addRule('shortdescription', 'Please enter a short description','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row=array($shortdescription->show());
	$objTable->addRow($row, NULL);
 	//long description text field

	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_longdescription",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	//Add the WYSWYG editor
	    $editor = $this->newObject('htmlarea', 'htmlelements');
	    $editor->name = 'longdescription';
	    $editor->height = '300px';
	    $editor->width = '550px';
	    $longdescription = '';
	    //To set the basic toolbar
	    //$editor->setBasicToolBar();
	    $editor->setContent($longdescription);
//$objTable->addCell($editor->showFCKEditor(), NULL, "top", "center", NULL, "colspan=\"2\"");
	$row=array($editor->showFCKEditor());
	//$form->addRule('longdescription', 'Please enter the long description','required');
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
