<?php


/**
 * This template will create new content item
 */

//first check if there is sections

if(!$this->_objSections->isSections())
{
    $str ='<script language="javascript" type="text/JavaScript">
           <![CDATA[
            alert(\'Please add a section before creating content\');
            ]]>
            </script>';
    print $str;
} else {


//initiate objects
$table = & $this->newObject('htmltable', 'htmlelements');
$titleInput = & $this->newObject('textinput', 'htmlelements');
$menuTextInput = & $this->newObject('textinput', 'htmlelements');
$bodyInput = & $this->newObject('htmlarea', 'htmlelements');
$introInput= & $this->newObject('htmlarea', 'htmlelements');
$h3 = &$this->newObject('htmlheading', 'htmlelements');
$button =  & $this->newObject('button', 'htmlelements');
$table2 = & $this->newObject('htmltable', 'htmlelements');
$frontPage = & $this->newObject('checkbox', 'htmlelements');
$published = & $this->newObject('checkbox', 'htmlelements');
$objOrdering = & $this->newObject('textinput', 'htmlelements');

$objForm = $this->newObject('form','htmlelements');


if($this->getParam('id') == '')
{

	$action = 'createcontent';
	$editmode = FALSE;

	$titleInput->value = '';
	$menuTextInput->value = '';
	$introInput->value = '';
	$published->setChecked(TRUE);
	$contentId = '';
	$arrContent = null;
	if( $this->getParam('frontpage') == 'true')
	{
	   $frontPage->setChecked(TRUE);
	}

} else {
	$action = 'editcontent';
	$contentId = $this->getParam('id');
	$editmode = TRUE;

	$arrContent = $this->_objContent->getContentPage($contentId);
	$titleInput->value = $arrContent['title'];
	$menuTextInput->value = $arrContent['menutext'];
	$introInput->setContent($arrContent['introtext']);
	$bodyInput->setContent($arrContent['body']);

	$frontPage->setChecked($this->_objFrontPage->isFrontPage($arrContent['id']));
	$published->setChecked($arrContent['published']);
}

if($editmode){
  $sections =& $this->newObject('textinput', 'htmlelements');
	$sections->name = 'parent';
	$sections->fldType = 'hidden';
	$sections->value = $arrContent['sectionid'];
	//Set ordering as hidden field
	$objOrdering->name = 'ordering';
	$objOrdering->fldType = 'hidden';
	$objOrdering->value = $arrContent['ordering'];
} else {
    if(isset($section) && !empty($section)){
      $sections = & $this->_objUtils->getTreeDropdown($section, TRUE);
    } else {
        $sections = & $this->_objUtils->getTreeDropdown(NULL, TRUE);
    }    
}
//setup form
$objForm->name='addfrm';
$objForm->setAction($this->uri(array('action'=> $action, 'id' => $contentId, 'frontpage' => $this->getParam('frontpage')),'cmsadmin'));
$objForm->setDisplayType(3);



$table->width='80%';
$table->border = '0';
//$table->cellspacing='1';
//$table->cellpadding='1';

$table2->width='80%';
$table2->border = '0';
//$table2->cellspacing='1';
//$table2->cellpadding='1';

//create heading
$h3->str = $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');


$titleInput->name = 'title';
$menuTextInput->name = 'menutext';
$objForm->addRule('title', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddtitle', 'cmsadmin'), 'required');
$bodyInput->name = 'body';
$introInput->name = 'intro';
$objForm->addRule('menutext', $this->objLanguage->languageText('mod_cmsadmin_pleaseaddmenutext', 'cmsadmin'), 'required');

$button->setToSubmit();
$button->value = 'Save';

$published->name = 'published';
$published->id = 'published';

$frontPage->name = 'frontpage';
$frontPage->id = 'frontpage';


$table->startRow();
$table->addCell($this->objLanguage->languageText('word_title'));
$table->addCell($titleInput->show());
if(!$editmode){
  $table->addCell($this->objLanguage->languageText('word_section'));
  $table->addCell($sections);
} else {
    $table->addCell($sections->show());
}    
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_cmsadmin_menuname', 'cmsadmin'));
$table->addCell($menuTextInput->show());
//$table->addCell('Categories');
//$table->addCell($category->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_cmsadmin_showonfrontpage', 'cmsadmin'));
$table->addCell($frontPage->show());
$table->addCell($this->objLanguage->languageText('word_published'));
$table->addCell($published->show());
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
$table2->addCell($this->objLanguage->languageText('mod_cmsadmin_maintext', 'cmsadmin'));
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

echo   $h3->show();

echo $objForm->show();
//echo $introInput->show();
}
?>
