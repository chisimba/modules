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
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, 'even');
	$row = array($objUser->fullName());	
	$objTable->addRow($row, 'even');


    	//parent goal label
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_parent",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	
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
	$objTable->addRow($row, 'even');
	//end Goals drop down list    	
	
	//type text box		
	$textinput = new textinput("goal_type",$goal_type);
	$textinput->size = 60;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_goalsType",'eportfolio').":</b>");	
	$objTable->addRow($row, 'even');
	$row = array($textinput->show());	
	$objTable->addRow($row, 'even');

	//start calendar
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('start', 'yes', 'no', $start);
	$row = array($startField);
	$objTable->addRow($row, 'even');

 	//priority text field
	$textinput = new textinput("priority",$priority);
	$textinput->size = 60;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_priority",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$row = array($textinput->show());	
	$objTable->addRow($row, 'even');


 	//status text field
	$textinput = new textinput("status",$status);
	$textinput->size = 60;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_status",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$row = array($textinput->show());	
	$objTable->addRow($row, 'even');

    	//status date text box
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_statusDate",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('status_date', 'yes', 'no', $status_date);
	$row = array($startField);
	$objTable->addRow($row, 'even');

 	//short description text field
	$textinput = new textinput("shortdescription",$shortdescription);
	$textinput->size = 60;
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$row = array($textinput->show());	
	$objTable->addRow($row, 'even');

 	
    	//Full description text field
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_longdescription",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	//Add the WYSWYG editor
	    $editor = $this->newObject('htmlarea', 'htmlelements');
	    $editor->name = 'longdescription';
	    $editor->height = '300px';
	    $editor->width = '450px';
	    $editor->setContent($longdescription);

	$row = array($editor->showFCKEditor());	   
	$objTable->addRow($row, 'even');
	
    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array( "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_goals',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
