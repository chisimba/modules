<?php


/**
 * This template will create or edit a section
 */

//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$titleInput = & $this->newObject('textinput', 'htmlelements');
$menuTextInput = & $this->newObject('textinput', 'htmlelements');
$bodyInput = & $this->newObject('htmlarea', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$sections = & $this->newObject('dropdown', 'htmlelements');
$category = & $this->newObject('dropdown', 'htmlelements');
$button =  & $this->newObject('button', 'htmlelements');
$objDropDown =& $this->newObject('dropdown', 'htmlelements');

if($this->getParam('id') == '')
{
	$action = 'createcategory';
	$editmode = FALSE;
	//create heading
    $h3->str = 'Category: New';
} else {
	$action = 'editcategory';
	$catId = $this->getParam('id');
	$editmode = TRUE;
	//create heading
    $h3->str = 'Category: New';
}

$objForm = $this->newObject('form','htmlelements');
     

//setup form
$objForm->name='addsectionfrm';
$objForm->setAction($this->uri(array('action'=>$action, 'id' => $catId),'cmsadmin'));
$objForm->setDisplayType(3);   


$table->width='60%';
$tablee->border='1';
$table->cellspacing='1';
$tablee->cellpadding='1';




$titleInput->name = 'title';
$titleInput->size = 50;

$menuTextInput->name = 'menutext';
$menuTextInput->size = 50;
$bodyInput->name = 'description';

$button->setToSubmit();
$button->value = 'Save';

if($editmode)
{
	$arrCat = $this->_objCategories->getCategory($catId);
	$titleInput->value = $arrCat['title'];
	$menuTextInput->value = $arrCat['menutext'];
	$bodyInput->value = $arrCat['description'];
	
} else {
	$titleInput->value = '';
	$menuTextInput->value = '';
	$bodyInput->value = '';
	
	
}

//title
$table->startRow();
$table->addCell('Category Title');
$table->addCell($menuTextInput->show());
$table->endRow();

//title name
$table->startRow();
$table->addCell('Category Menu Text');
$table->addCell($titleInput->show());
$table->endRow();


//section 
$table->startRow();
$table->addCell('Section');
$objDropDown->name = 'section';
$objDropDown->addFromDB($this->_objSections->getSections(), 'menutext','id',$arrCat['sectionid']);
$table->addCell($objDropDown->show());
$table->endRow();

//image
$table->startRow();
$table->addCell('Image');
$table->addCell($this->_objUtils->getImageList('image'));
$table->endRow();

//image postion
$table->startRow();
$table->addCell('Image Position');
$table->addCell($this->_objUtils->getImagePostionList('imageposition'));
$table->endRow();


//Ordering
$table->startRow();
$table->addCell('Ordering');
if(editmode)
{	
	$table->addCell($this->_objSections->getOrderList('ordering'));
} else {
	$table->addCell('New items default to the last place. Ordering can be changed after this item is saved.');
}
$table->endRow();


//access level
$table->startRow();
$table->addCell('Access Level');
$table->addCell($this->_objUtils->getAccessList('access'));
$table->endRow();


//published
$table->startRow();
$table->addCell('Published');
$table->addCell($this->_objUtils->getYesNoRadion('published'));
$table->endRow();

//description
$table->startRow();
$table->addCell('Description');
$table->addCell($bodyInput->show());
$table->endRow();

//button
$table->startRow();
$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();

$objForm->addToForm($table);

print  $h3->show();

print $objForm->show();


?>