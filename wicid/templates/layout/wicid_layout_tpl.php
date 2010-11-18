<?php
$baseFolder = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$nav = $this->objUtils->getTree($baseFolder, $selected);

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('tabcontent', 'htmlelements');

$searchForm = new form('filesearch', $this->uri(array('action' => 'search')));
$searchForm->method = 'GET';
$hiddenInput = new hiddeninput('module', 'filemanager');
$searchForm->addToForm($hiddenInput->show());

$hiddenInput = new hiddeninput('action', 'search');
$searchForm->addToForm($hiddenInput->show());

$textinput = new textinput('filequery', $this->getParam('filequery'));
$searchForm->addToForm($textinput->show());

$button = new button('search', $this->objLanguage->languageText('word_search', 'system', 'Search'));
$button->setToSubmit();
$searchForm->addToForm($button->show());

//get file list
// Create an Instance of the CSS Layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');

$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_name', 'wicid', 'WICID');

$leftColumn = $header->show();

$leftColumn .= $searchForm->show();
$leftColumn .= '<div class="filemanagertree">' . $nav . '</div>';
$cssLayout->setLeftColumnContent($leftColumn);

$cssLayout->setMiddleColumnContent($this->getContent());
// Display the Layout
echo $cssLayout->show();
?>