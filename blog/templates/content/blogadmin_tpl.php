<?php

//initiate objects
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
//$this->loadClass('heading', 'htmlelements');
$this->loadClass('href', 'htmlelements');

$tt = $this->newObject('domtt', 'htmlelements');
$pane = &$this->newObject('tabpane', 'htmlelements');

$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');
$leftCol = NULL;
$middleColumn = NULL;
//left menu section
$leftCol = $leftMenu->show();

//set up the right column featureboxen
//////////

//get the categories layout sorted
$cats = $this->objDbBlog->getAllCats($userid);
//create a table to view the categories
$cattable = $this->getObject('htmltable', 'htmlelements');
$cattable->cellpadding = 3;
//set up the header row
$cattable->startHeaderRow();
$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_parent", "blog"));
//$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_name", "blog"));
$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_nicename", "blog"));
$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_descrip", "blog"));
$cattable->addHeaderCell($this->objLanguage->languageText("mod_blog_cathead_count", "blog"));
$cattable->endHeaderRow();
if(!empty($cats))
{
	foreach($cats as $rows)
	{
		//start the cats rows
		$cattable->startRow();
		$cattable->addCell($rows['cat_parent']);
		//$cattable->addCell($rows['cat_name']);
		$cattable->addCell($rows['cat_nicename']);
		$cattable->addCell($rows['cat_desc']);
		$cattable->addCell($rows['cat_count']);
		$cattable->endRow();
	}
	$ctable = $cattable->show();
}
else {
	$ctable = $this->objLanguage->languageText("mod_blog_nocats", "blog");
}

//add a new category form:
$catform = new form('catadd', $this->uri(array(
    'action' => 'catadd'
)));

$cfieldset = $this->getObject('fieldset', 'htmlelements');
$cfieldset->setLegend($objLanguage->languageText('mod_blog_catname', 'blog'));
$catadd = $this->getObject('htmltable', 'htmlelements');
$catadd->cellpadding = 5;
//category name field
$catadd->startRow();
$clabel = new label($objLanguage->languageText('mod_blog_catname', 'blog') .':', 'input_catname');
$catname = new textinput('catname');
$catadd->addCell($clabel->show() , 150, NULL, 'right');
$catadd->addCell($catname->show());
$catadd->endRow();


//category parent field (dropdown)


$cfieldset->addContent($catadd->show());
$catform->addToForm($cfieldset->show());
$this->objCButton = &new button($objLanguage->languageText('word_update', 'blog'));
$this->objCButton->setValue($objLanguage->languageText('word_update', 'blog'));
$this->objCButton->setToSubmit();
$catform->addToForm($this->objCButton->show());
$catform = $catform->show();





//Middle column - dashboard
$pane->addTab(array(
    'name' => 'categories',
    'content' => $catform
));
$pane->addTab(array(
    'name' => 'links',
    'content' => 'blogroll etc'
));

$middleColumn .= $pane->show();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>