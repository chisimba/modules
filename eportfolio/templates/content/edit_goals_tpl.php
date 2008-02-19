<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$objCheck = $this->loadClass('checkbox', 'htmlelements');
	//$userid = $this->objUser->userId();
	$mygoals = $this->objDbGoalsList->getUserGoals();
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editGoals",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editgoalsconfirm',
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
			//Dont select the current goal
			if($id !== $goals['id'])
			{
				$dropdown->addOption($goals['id'], $goals['shortdescription']);
				$dropdown->setSelected($parentid);
			}
		}
		
	}else{
		$dropdown->addOption('None', "-Root-");	
	}
	
	$row = array($dropdown->show());	
	$objTable->addRow($row, NULL);
	//end Goals drop down list    	
	
	//type text box		
	$textinput = new textinput("goal_type",$goal_type);
	$textinput->size = 60;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_goalsType",'eportfolio').":</b>");	
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);

	//start calendar
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('start', 'yes', 'no', $start);
	$row = array($startField);
	$objTable->addRow($row, NULL);

 	//priority text field
	$textinput = new textinput("priority",$priority);
	$textinput->size = 60;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_priority",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);


 	//status text field
	$textinput = new textinput("status",$status);
	$textinput->size = 60;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_status",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$row = array($textinput->show());	
	$objTable->addRow($row, NULL);

    	//status date text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_statusDate",'eportfolio').":</b>");
	$objTable->addRow($row, NULL);
	$startField = $this->objPopupcal->show('status_date', 'yes', 'no', $status_date);
	$row = array($startField);
	$objTable->addRow($row, NULL);

 	//short description text field
	$textinput = new textinput("shortdescription",$shortdescription);
	$textinput->size = 60;
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
                'action'=>'view_goals'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());
	echo $form->show();
?>
