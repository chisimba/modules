<?php

$hasAccess = $this->objEngine->_objUser->isContextLecturer();
$hasAccess|= $this->objEngine->_objUser->isAdmin();
$this->setVar('pageSuppressXML',true);
if( !$hasAccess ) {
		// Redirect
		return $this->nextAction( 'main', array() );
		break;
} else {
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass('textarea','htmlelements');
	$this->loadClass("button","htmlelements");
	$this->loadClass("htmltable", 'htmlelements');
	$this->loadClass('dropdown', 'htmlelements');
	$objCheck = $this->loadClass('checkbox', 'htmlelements');
	$categoryList = $this->objDbCategoryList->getByItem();
	$objWindow =& $this->newObject('windowpop','htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_eportfolio_addCategorytype",'eportfolio');
	if (!empty($categoryList))
	{

	echo $objHeading->show();
	
	$form = new form("add", 
		$this->uri(array(
	    		'module'=>'eportfolio',
	   		'action'=>'addcategorytypeconfirm'
	)));
	$objTable = new htmltable();
	$objTable->width='100%';
	$objTable->attributes=" align='left' border='0'";
	$objTable->cellspacing='5';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->startRow();
	$objTable->addCell($row[0], 140,'top','right');
	$row = array($objUser->fullName());	
	$objTable->addCell($row[0], Null,'top','left');
	$objTable->endRow();	

	
	//category text box		
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_category",'eportfolio').":</b>");	
	$objTable->startRow();
	$objTable->addCell($row[0], 140,'top','right');
	//Goals Drop down list
	$dropdown = new dropdown('categoryid');
	
	if (!empty($categoryList))
	{
		foreach ($categoryList as $categories)
		{

			$dropdown->addOption($categories['id'], $categories['category']);
			
		}
		
	}else{
		$dropdown->addOption('None', "-No Categories Present-");	
	}
	
	$row = array($dropdown->show());
	$objTable->addCell($row[0], Null,'top','left');
	$objTable->endRow();	

	//category type text box		
	$categorytype = new textinput("categorytype","");
	$categorytype->size = 30;
	$form->addRule('categorytype','Please enter the Category Type','required');
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_categoryType",'eportfolio').":</b>");	
	$objTable->startRow();
	$objTable->addCell($row[0], 140,'top','right');
	$row = array($categorytype->show());	
	$objTable->addCell($row[0], Null,'top','left');
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
                'action'=>'view_categorytype'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->startRow();
	$objTable->addCell('&nbsp;', 140,'top','right');
	$objTable->addCell($row[0], Null,'top','left');
	$objTable->endRow();	
	$form->addToForm($objTable->show());
	echo $form->show();
	}else{
		return $this->nextAction( 'view_category', array() );
		break;		
	}
}
?>
