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
	$mytype = 'Priority';
	$mycategorytypeList = $this->objDbCategorytypeList->listCategorytype($mytype);

	$objCheck = $this->loadClass('checkbox', 'htmlelements');
	$mygoals = $this->objDbGoalsList->getUserGoals();
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addGoal",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addgoalsconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='30';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);
	$row = array($objUser->fullName());	
	$objTable->addRow($row, NULL);


    	//parent goal label
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_parent",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	
	//Goals Drop down list
	$dropdown = new dropdown('parentid');
	if (!empty($mygoals))
	{
		$dropdown->addOption('None', "-Root-");
		foreach ($mygoals as $goals)
		{
			$dropdown->addOption($goals['id'], $goals['shortdescription']);
		}
		
	}else{
		$dropdown->addOption('None', "-Root-");	
	}
	
	$row = array($dropdown->show());	
	$objTable->addRow($row, NULL);
	//end Goals drop down list    	
	
    	//type drop down list	
	$mydropdown = new dropdown('goal_type');
	
	if (!empty($categorytypeList))
	{
		foreach ($categorytypeList as $categories)
		{

			$mydropdown->addOption($categories['id'], $categories['type']);

			
		}
		
	}else{
		$mydropdown->addOption('None', "-There are no Types-");	
	}

	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_goalsType",'eportfolio').":</b>");	
	$objTable->addRow($row, NULL);
	$row = array($mydropdown->show());	
	$objTable->addRow($row, NULL);

	//start calendar
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('start', 'yes', 'no', "");
	$form->addRule('start', 'Please enter the start date','required');
	$row = array($startField);
	$objTable->addRow($row, NULL);

    	//priority drop down list	
	$dropdown = new dropdown('priority');
	
	if (!empty($mycategorytypeList))
	{
		foreach ($mycategorytypeList as $categories)
		{

			$dropdown->addOption($categories['id'], $categories['type']);
			
			
		}
		
	}else{
		$dropdown->addOption('None', "-There are no Types-");	
	}

	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_priority",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($dropdown->show());	
	$objTable->addRow($row, NULL);


 	//status text field
	$textinput = new textinput("status","");
	$textinput->size = 60;
	$form->addRule('status', 'Please enter the status','required');
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_status",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);

    	//status date text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_statusDate",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('status_date', 'yes', 'no', "");
	$form->addRule('status_date', 'Please enter the status date','required');
	$row = array($startField);
	$objTable->addRow($row, NULL);

 	//short description text field
	$textinput = new textinput("shortdescription","");
	$textinput->size = 60;
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
	    //To set the basic toolbar
	    //$editor->setBasicToolBar();
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
                'action'=>'view_goals'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());
	echo $form->show();
?>
