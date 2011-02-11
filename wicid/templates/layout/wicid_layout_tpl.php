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

//Create a table to hold the search params
$ftable = &$this->newObject("htmltable", "htmlelements");

//Set width
$ftable->width = "20%";

//Create a dropdown to hold the search parameters
$filterdrops = new dropdown('filter');

$filterdrops->addOption("Ref No");
$filterdrops->addOption("Title");
$filterdrops->addOption("Owner");
$filterdrops->addOption("Telephone");
$filterdrops->addOption("Date");

//Create a submit button
$filterbutton = new button('filtersearch', "Search");
$filterbutton->setToSubmit();

$ftable->startRow();
$ftable->addCell("Search by: ");
$ftable->endRow();

$ftable->startRow();
$ftable->addCell($filterdrops->show());
$ftable->endRow();

$ftable->startRow();
$ftable->addCell($filterbutton->show());
$ftable->endRow();


//Add a form to contain the search feature
$form = new form('searchdocs', $this->uri(array('action' => 'filtersearch')));
$form->addToForm($ftable->show());
//Add search table to fieldset
$filterset = new fieldset();
$filterset->setLegend('Search Documents');
$filterset->addContent($form->show());

$filters = $filterset->show();


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

//Disable old search
//$leftColumn .= $searchForm->show();
//New Search
$leftColumn .= $filters;
$leftColumn .=  "test link";
$filters = $filterset->show();

$leftColumn .= '<div class="filemanagertree">' . $nav . '</div>';
$cssLayout->setLeftColumnContent($leftColumn);

$cssLayout->setMiddleColumnContent($this->getContent());
// Display the Layout
echo $cssLayout->show();
?>