<?php


/**
 * This template will create new content item
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$titleInput = & $this->newObject('textinput', 'htmlelements');
$menuTextInput = & $this->newObject('textinput', 'htmlelements');
$bodyInput = & $this->newObject('htmlarea', 'htmlelements');
$introInput= & $this->newObject('htmlarea', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$sections = & $this->newObject('dropdown', 'htmlelements');
$category = & $this->newObject('dropdown', 'htmlelements');
$button =  & $this->newObject('button', 'htmlelements');
$table2 = & $this->newObject('htmltable', 'htmlelements');

$objForm = $this->newObject('form','htmlelements');
     

if($this->getParam('id') == '')
{
	
	$action = 'createcontent';
	$editmode = FALSE;
	
	$titleInput->value = '';
	$menuTextInput->value = '';
	$introInput->value = '';
	

	
} else {
	$action = 'editcontent';
	$contentId = $this->getParam('id');
	$editmode = TRUE;
	
	$arrContent = $this->_objContent->getContentPage($contentId);
	$titleInput->value = $arrContent['title'];
	$menuTextInput->value = $arrContent['menutext'];
	$introInput->setContent($arrContent['introtext']);
	$bodyInput->setContent($arrContent['fulltext']);

	
}


//setup form
$objForm->name='addfrm';
$objForm->setAction($this->uri(array('action'=> $action, 'id' => $contentId),'cmsadmin'));
$objForm->setDisplayType(1);   



$table->width='80%';
$table->border = '0';
//$table->cellspacing='1';
//$table->cellpadding='1';

$table2->width='80%';
$table2->border = '0';
//$table2->cellspacing='1';
//$table2->cellpadding='1';

//create heading
$h3->str = 'Content Item: New';


$titleInput->name = 'title';
$menuTextInput->name = 'menutext';
$bodyInput->name = 'body';
$introInput->name = 'intro';

$sections->name= 'section';
$sections->addFromDB($this->_objSections->getSections(),'title','id');
$sections->label='Sections';

$category->name= 'catid';
$category->addFromDB($this->_objCategories->getCategories(),'title','id');
$category->label='Sections';

$button->setToSubmit();
$button->value = 'Save';


$table->startRow();
$table->addCell('Title');
$table->addCell($titleInput->show());
$table->addCell('Section');
$table->addCell($sections->show());

$table->endRow();

$table->startRow();
$table->addCell('Menu Text');
$table->addCell($menuTextInput->show());
$table->addCell('Categories');
$table->addCell($category->show());
$table->endRow();

$table2->startRow();
$table2->addCell($table->show());
$table2->endRow();

//inrto input
$table2->startRow();
$table2->addCell('Intro Input (required)');
$table2->endRow();
$table2->startRow();
$table2->addCell($introInput->show());
$table2->endRow();

//body
$table2->startRow();
$table2->addCell('Main Text');
$table2->endRow();
$table2->startRow();
$table2->addCell($bodyInput->show());
$table2->endRow();

$table2->startRow();
$table2->addCell($button->show());
$table2->endRow();
/*
$table->startRow();
$table->addCell($bodyInput->show());
$table->endRow();


$table->startRow();
$table->addCell($button->show());
$table->endRow();
*/
$objForm->addToForm($table2);
//$objForm->addToForm($bodyInput);
//$objForm->addToForm($button);

print  $h3->show();

print $objForm->show();


?>