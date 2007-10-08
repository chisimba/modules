<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');

// Set columns to 2
$cssLayout->setNumColumns(2);
$leftMenu = NULL;

$rightSideColumn = NULL;

$leftCol = NULL;
$middleColumn = NULL;

$leftCol .= $objSideBar->show();

$this->loadClass('href', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->objUser = $this->getObject('user', 'security');

$cform = new form('addserver', $this->uri(array(
	'action' => 'addserver'
	)));

//add rules
$cform->addRule('ip', $this->objLanguage->languageText("mod_cache_phrase_ipreq", "cache") , 'required');
$cform->addRule('port', $this->objLanguage->languageText("mod_cache_phrase_portreq", "cache") , 'required');
// $cform->addRule('port', $this->objLanguage->languageText("mod_cache_phrase_portnumreq", "cache") , 'numeric');

//start a fieldset
$cfieldset = $this->getObject('fieldset', 'htmlelements');
$cadd = $this->newObject('htmltable', 'htmlelements');
$cadd->cellpadding = 3;

//IP textfield
$cadd->startRow();
$ciplabel = new label($this->objLanguage->languageText('mod_cache_ip', 'cache') . ':', 'input_ip');
$ip = new textinput('ip');
$ip->extra = ' style="width:100%;" ';
if (isset($cache['ip'])) {
	$ip->setValue(htmlentities($cache['ip'], ENT_QUOTES));
}
$cadd->addCell($ciplabel->show());
$cadd->addCell($ip->show());
$cadd->endRow();

//Port
$cadd->startRow();
$plabel = new label($this->objLanguage->languageText('mod_cache_port', 'cache') . ':', 'input_port');
$port = new textinput('port');
$port->extra = ' style="width:100%;" ';
if (isset($cache['port'])) {
	$port->setValue($cache['port']);
}
$cadd->addCell($plabel->show());
$cadd->addCell($port->show());
$cadd->endRow();

//end off the form and add the buttons
$this->objCButton = new button($this->objLanguage->languageText('word_save', 'system'));
$this->objCButton->setValue($this->objLanguage->languageText('word_save', 'system'));
$this->objCButton->setToSubmit();
$cfieldset->addContent($cadd->show());
$cform->addToForm($cfieldset->show());
$cform->addToForm($this->objCButton->show());
$cform = $cform->show();

// now the table of existing servers...



$middleColumn .= $cform;

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());

echo $cssLayout->show();
?>