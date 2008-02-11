<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
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
	$objTable->cellpadding='12'; 
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, 'odd');
	$row = array($objUser->fullName());
	$objTable->addRow($row, 'odd');	
    	//contexttitle drop down field
	
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_contexttitle",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	//Context Drop down list
	$dropdown = new dropdown('contexttitle');
	
	if (!empty($usercontexts))
	{
		$dropdown->addOption('None', "None");
		foreach ($usercontexts as $mycontext)
		{
			$dropdown->addOption($mycontext['contextcode'], $mycontext['title']);

		}
	
		
	}else{
		
		$dropdown->addOption('None', "You are not registered for any course");	
	}
		
	$row=array($dropdown->show());
	$objTable->addRow($row, 'even');
	//end Context drop down list
	
    	//activity_type text box		
	$textinput = new textinput("activity_type","");
	$textinput->size = 60;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitytype",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');	
	$row=array($textinput->show());
	$objTable->addRow($row, 'even');
    	//activity start text box	
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activitystart",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('activityStart', 'yes', 'no', '');
	$row = array($startField);
	$objTable->addRow($row, 'even');
    	//activity finish text box	
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_activityfinish",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('activityFinish', 'yes', 'no', '');
	$row = array($startField);
	$objTable->addRow($row, 'even');
    	//short description text box
	$textinput = new textarea("shortdescription","");
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_shortdescription",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');	
	$row=array($textinput->show());
	$objTable->addRow($row, 'even');
 	//long description text field

	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_longdescription",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');	
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
	$objTable->addRow($row, 'even');
    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array( "<a href=\"". $this->uri(array(
	'module'=>'eportfolio','action'=>'view_activity',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
