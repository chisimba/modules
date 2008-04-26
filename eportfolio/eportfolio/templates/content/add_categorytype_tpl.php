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
	$objTable->width='30';
	$objTable->attributes=" align='center' border='0'";
	$objTable->cellspacing='12';
	$row = array("<b>".$objLanguage->code2Txt("word_name").":</b>");
	$objTable->addRow($row, NULL);
	$row = array($objUser->fullName());	
	$objTable->addRow($row, NULL);


	
	//category text box		
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_category",'eportfolio').":</b>");	
	$objTable->addRow($row, NULL);
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
	$objTable->addRow($row, NULL);

	//category type text box		
	$categorytype = new textinput("categorytype","");
	$categorytype->size = 60;
	$form->addRule('categorytype','Please enter the Category Type','required');
	$row=array("<b>".$label = $objLanguage->languageText("mod_eportfolio_categoryType",'eportfolio').":</b>");	
	$objTable->addRow($row, NULL);
	$row = array($categorytype->show());	
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
                'action'=>'view_categorytype'
            )));
        $objCancel->link = $buttonCancel->show();
        $linkCancel = $objCancel->show();  
	$row = array($button->show().' / '.$linkCancel);
	$objTable->addRow($row, NULL);
	$form->addToForm($objTable->show());
	echo $form->show();
	}else{
		return $this->nextAction( 'view_category', array() );
		break;		
	}
}
?>
