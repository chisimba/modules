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


if($this->getParam('id') == '')
{
	$action = 'createsection';
	$editmode = FALSE;
	$sectionId='';
} else {
	$action = 'editsection';
	$sectionId = $this->getParam('id');
	$editmode = TRUE;
}

$objForm = $this->newObject('form','htmlelements');
     

//setup form
$objForm->name='addsectionfrm';
$objForm->setAction($this->uri(array('action'=>$action, 'id' => $sectionId),'cmsadmin'));
$objForm->setDisplayType(3);   


$table->width='60%';
$tablee->border='1';
$table->cellspacing='1';
$tablee->cellpadding='1';

//create heading
$h3->str = 'Section: New';


$titleInput->name = 'title';
$titleInput->size = 50;
$menuTextInput->name = 'menutext';
$menuTextInput->size = 50;
$bodyInput->name = 'description';

$button->setToSubmit();
$button->value = 'Save';

if($editmode)
{
	$arrSection = $this->_objSections->getSection($sectionId);
	$titleInput->value = $arrSection['title'];
	$menuTextInput->value = $arrSection['menutext'];
	$bodyInput->value = $arrSection['description'];
	$layout = $arrSection['layout'];
	$selected = $arrSection['image'];
	$imageSRC = $this->_objConfig->getsiteRoot().'/usrfiles/media'.$selected;
	$isPublished = ($arrSection['published'] == 1) ? 'Yes' : 'No';
	
} else {
	$titleInput->value = '';
	$menuTextInput->value = '';
	$bodyInput->value = '';
	$selected = '';
	$layout = 0;
	$isPublished = 'Yes';
	$imageSRC = $this->_objConfig->getsiteRoot().'skins/_common/blank.png';
}

//title
$table->startRow();
$table->addCell('Title');
$table->addCell($titleInput->show().'<p/>');
$table->endRow();

//title name
$table->startRow();
$table->addCell('Menu Text');
$table->addCell($menuTextInput->show().'<p/>');
$table->endRow();

//image
$table->startRow();
$table->addCell('Image');
$table->addCell($this->_objUtils->getImageList('image', $selected).'&nbsp;<img src="'.$imageSRC.'"  name="imagelib" width="80" height="80" border="2" alt="Preview" /><p/>', 'top');

$table->endRow();

//image postion
$table->startRow();
$table->addCell('Image Position');
$table->addCell($this->_objUtils->getImagePostionList('imageposition').'<p/>');
$table->endRow();

$table->startRow();
$table->addCell('Layout');

$table->addCell($this->_objUtils->getLayoutOptions('sectionlayout', $this->getParam('id')).'<p/>');
$table->endRow();

//Ordering
$table->startRow();
$table->addCell('Ordering');
if($editmode)
{	
	$table->addCell($this->_objSections->getOrderList('ordering').'<p />');
} else {
	$table->addCell('New items default to the last place. Ordering can be changed after this item is saved.').'<p/>';
}
$table->endRow();


//access level
$table->startRow();
$table->addCell('Access Level');
$table->addCell($this->_objUtils->getAccessList('access').'<p/>');
$table->endRow();


//published
$table->startRow();
$table->addCell('Published');
$table->addCell($this->_objUtils->getYesNoRadion('published', $isPublished));
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