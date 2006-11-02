<?php
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$objLabel =& $this->newObject('label', 'htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_readinglist_edit",'readinglist');
	echo $objHeading->show();
	$form = new form("edit", 
		$this->uri(array(
	    		'module'=>'readinglist',
	   		'action'=>'editConfirm',
			'id'=>$id
	)));
	
	$objTable =& $this->newObject('htmltable','htmlelements');
        $objTable->width='20';
        $objTable->attributes=" align='center' border=0";
        $objTable->cellspacing='12';
        $objTable->cellpadding='12';
        $row =
 array("<b>".$objLanguage->code2Txt("word_name").":</b>",
        $objUser->fullName());
        $objTable->addRow($row, 'odd');
        $row =
        array("<b>".ucwords($objLanguage->code2Txt("mod_context_context",'readinglist')).":</b>",
        $contextTitle);
        $objTable->addRow($row, 'odd');

	//... Author text box
	$textinput = new textinput("author",$author);
        $textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_author",'readinglist').":</b>",
        $textinput->show());

    	//Title text box
        $objTable->addRow($row, 'even');
	$textinput = new textinput("title",$title);
        $textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_title",'readinglist').":</b>",
        $textinput->show());

    	//Publisher text box
        $objTable->addRow($row, 'even');
        $textinput = new textinput("publisher",$publisher);
        $textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_publisher",'readinglist').":</b>",
        $textinput->show());
						     
	
    	//Publishing year text box
        $objTable->addRow($row, 'even');
        $textinput = new textinput("publishingYear",$publishingYear);
        $textinput->size = 4;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_year",'readinglist').":</b>",
        $textinput->show());

        //Link text box
        $objTable->addRow($row, 'even');
        $textinput = new textinput("link",$link);
        $textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_link",'readinglist').":</label></b>",
        $textinput->show());			

	//Publication text box
	$objTable->addRow($row, 'even');
        $textinput = new textinput("publication",$publication);
        $textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_publication",'readinglist').":</b>",
        $textinput->show());
	$objTable->addRow($row, 'even');

    	//Save button
	$button = new button("submit",
	$objLanguage->languageText("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show());
	$objTable->addRow($row, 'even');
	$row = array("<a href=\"". $this->uri(array(
	'module'=>'readinglist',)). "\">".
	$objLanguage->languageText("word_cancel") . "</a>");	//word_cancel
	$objTable->addRow($row, 'even');
	$form->addToForm($objTable->show());
	echo $form->show();
?>
