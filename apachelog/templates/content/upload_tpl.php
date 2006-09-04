<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('formatfilesize', 'files');

$this->loadClass('form', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$userMenu  = &$this->newObject('usermenu','toolbar');

// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
// Set columns to 2
$cssLayout->setNumColumns(2);

// Add Post login menu to left column
$leftSideColumn ='';
$leftSideColumn = $userMenu->show();

$middleColumn = NULL;

//trigger_error("ooooh sheeet");

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_apachelog_header', 'apachelog');

$middleColumn = $header->show();

//create the form
$objForm = new form('logupload',$this->uri(array('action'=>'parselogfile')));
$objForm->displayType = 4;
$objForm->addToFormEx($objLanguage->languageText('mod_apachelog_ulogfile', 'apachelog'));

$objSelectFile = $this->newObject('selectfile', 'filemanager');
$objSelectFile->name = 'logfile';
$objSelectFile->restrictFileList = array('log');
$objForm->addToFormEx($objSelectFile->show());


$this->objButton=&new button($objLanguage->languageText('phrase_uploadfiles', 'system'));
$this->objButton->setValue($objLanguage->languageText('phrase_uploadfiles', 'system'));
//$this->objButton->setOnClick('alert(\'Processing\')');
$this->objButton->setToSubmit();
$objForm->addToFormEx($this->objButton->show());



$middleColumn .= $objForm->show();
//add left column
$cssLayout->setLeftColumnContent($leftSideColumn);
//add middle column
$cssLayout->setMiddleColumnContent($middleColumn);
echo $cssLayout->show();