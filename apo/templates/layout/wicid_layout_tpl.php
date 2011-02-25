<?php
$baseFolder = $this->objSysConfig->getValue('FILES_DIR', 'apo');
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

$textinput = new textinput('query');
$searchForm->addToForm($textinput->show());

$button = new button('search', $this->objLanguage->languageText('word_search', 'system', 'Search'));
$button->setToSubmit();
$searchForm->addToForm($button->show());

//get file list
// Create an Instance of the CSS Layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');

$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_name', 'wicid', 'APO');

$leftColumn = $header->show();

//$leftColumn .= $searchForm->show();

$leftColumn .= '<div class="filemanagertree">' . $nav . '</div>';
$cssLayout->setLeftColumnContent($leftColumn);


$cssLayout->setMiddleColumnContent($this->getContent());
// Display the Layout
echo $cssLayout->show();
?>