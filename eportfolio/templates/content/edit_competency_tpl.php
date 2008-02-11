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
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_editcompetency",'eportfolio');
	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'editcompetencyconfirm',
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
	
	//type text box		
	$textinput = new textinput("competency_type",$competency_type);
	$textinput->size = 60;
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_competencytype",'eportfolio').":</b>");	
	$objTable->addRow($row, 'even');
	$row = array($textinput->show());	
	$objTable->addRow($row, 'even');

	//award date calendar
	$row = array("<b>".$label = $objLanguage->languageText("mod_eportfolio_qclawarddate",'eportfolio').":</b>");
	$objTable->addRow($row, 'even');
	$startField = $this->objPopupcal->show('award_date', 'yes', 'no', $award_date);
	$row = array($startField);
	$objTable->addRow($row, 'even');

 	//short description text field
	$textinput = new textarea("shortdescription",$shortdescription);
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
	'module'=>'eportfolio','action'=>'view_competency',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
