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
$parent = & $this->newObject('dropdown', 'htmlelements');
$button =  & $this->newObject('button', 'htmlelements');
$objRootId = & $this->newObject('textinput', 'htmlelements');
$objParentId = & $this->newObject('textinput', 'htmlelements');
$objCount = & $this->newObject('textinput', 'htmlelements');
$objOrdering = & $this->newObject('textinput', 'htmlelements');

if($this->getParam('id') == ''){
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
$objForm->id='addsectionfrm';
if(isset($parentid)){
  $objForm->setAction($this->uri(array('action'=>$action, 'id' => $sectionId, 'parentid' => $parentid),'cmsadmin'));
} else {
    $objForm->setAction($this->uri(array('action'=>$action, 'id' => $sectionId),'cmsadmin'));
}
$objForm->setDisplayType(3);   


$table->width='60%';
$table->cellspacing='2';
$table->cellpadding='2';

//the title
$titleInput->name = 'title';
$titleInput->id = 'title';
$titleInput->size = 50;

$objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseentertitle', 'cmsadmin'),'required');

$menuTextInput->name = 'menutext';
$menuTextInput->size = 50;
$objForm->addRule('menutext', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddmenutext', 'cmsadmin'), 'required');

$bodyInput->name = 'description';

//$button->setToSubmit();
$button->value = 'Save';
$button->setToSubmit();//onclick = 'return validate_addsectionfrm_form(this.form) ';
if($editmode)
{
	$arrSection = $this->_objSections->getSection($sectionId);
	$titleInput->value = $arrSection['title'];
	$menuTextInput->value = $arrSection['menutext'];
	$bodyInput->value = $arrSection['description'];
	$layout = $arrSection['layout'];
	$selected = $arrSection['image'];
	$imageSRC = $objSkin->getSkinUrl().$selected;//$this->_objConfig->getsiteRoot().'/usrfiles/media'.$selected;
	$isPublished = ($arrSection['published'] == 1) ? 'Yes' : 'No';
	//Set rootid as hidden field
	$objRootId->name = 'rootid';
	$objRootId->fldType = 'hidden';
	$objRootId->value = $arrSection['rootid'];
	//Set parentid as hidden field
	$objParentId->name = 'parent';
	$objParentId->fldType = 'hidden';
	$objParentId->value = $arrSection['parentid'];
	//Set parentid as hidden field
	$objCount->name = 'count';
	$objCount->fldType = 'hidden';
	$objCount->value = $arrSection['count'];
	//Set parentid as hidden field
	$objOrdering->name = 'ordering';
	$objOrdering->fldType = 'hidden';
	$objOrdering->value = $arrSection['ordering'];
	
} else {
	$titleInput->value = '';
	$menuTextInput->value = '';
	$bodyInput->value = '';
	$selected = '';
	$layout = 0;
	$isPublished = 'Yes';
	$imageSRC = $this->_objConfig->getsiteRoot().'skins/_common/blank.png';
	
}

//Add form elements to the table
if(!$editmode){
  $table->startRow();
  $table->addCell($this->objLanguage->languageText('mod_cmsadmin_parentfolder', 'cmsadmin'));
  if(isset($parentid)){
    $table->addCell($this->_objUtils->getTreeDropdown($parentid).'<p/>');
  } else {
      $table->addCell($this->_objUtils->getTreeDropdown().'<p/>');
  }  
  $table->endRow();
} else {
    $table->startRow();
    $table->addCell($objParentId->show().$objRootId->show().$objCount->show().$objOrdering->show());
    $table->endRow();
}
//title name
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_title'));
$table->addCell($titleInput->show().'<p/>');
$table->endRow();

//menu text name
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
$table->addCell($menuTextInput->show().'<p/>');
$table->endRow();

//image
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_image'));
$table->addCell($this->_objUtils->getImageList('image',$objForm->name, $selected).'&nbsp;<span class="thumbnail"><img src="'.$imageSRC.'" 
					 id="imagelib" name="imagelib" border="2" alt="Preview" /></span><p/>', 'top');

$table->endRow();

//image postion
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_cmsadmin_imageposition', 'cmsadmin'));
$table->addCell($this->_objUtils->getImagePostionList('imageposition').'<p/>');
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('word_layout'));
$table->addCell($this->_objUtils->getLayoutOptions('sectionlayout', $this->getParam('id')).'<p/>');
$table->endRow();

//access level
$table->startRow();
$table->addCell($this->objLanguage->languageText('phrase_accessevel'));
$table->addCell($this->_objUtils->getAccessList('access').'<p/>');
$table->endRow();


//published
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_published'));
$table->addCell($this->_objUtils->getYesNoRadion('published', $isPublished));
$table->endRow();

//description
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_description'));
$table->addCell($bodyInput->show());
$table->endRow();

//button
$table->startRow();
$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();

$objForm->addToForm($table);

//create heading
if($editmode){
  $h3->str = $this->objLanguage->languageText('mod_cmsadmin_editsection', 'cmsadmin');
} else {
    $h3->str = $this->objLanguage->languageText('mod_cmsadmin_addnewsection', 'cmsadmin');
}

print  $h3->show();

print $objForm->show();


?>

<script language="javascript" type="text/javascript">
<![CDATA[
	function changeImage(el, frm)
	{
		fe = document.getElementById('imagelib');
		//alert(fe.name);
		if (el.options[el.selectedIndex].value!='')
		{
			fe.src= 'skins/echo' + el.options[el.selectedIndex].value;
		} else {
			fe.src='http://localhost/5ive/app/skins/_common/blank.png';
		}
			
	}
	

]]>
	</script>
