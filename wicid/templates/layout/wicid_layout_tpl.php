<?php
$baseFolder = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$nav = $this->objUtils->getTree($baseFolder, $selected);
$managenav = $this->objUtils->getManageTree($baseFolder, $selected);

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable', 'htmlelements');
$this->loadClass('tabcontent', 'htmlelements');
$this->loadClass('radio', 'htmlelements');

//Create a table to hold the search params
$ftable = &$this->newObject("htmltable", "htmlelements");

//Set width
$ftable->width = "20%";

//Create a dropdown to hold the search parameters
/*$filterdrops = new dropdown('filter11');

$filterdrops->addOption("Ref No");
$filterdrops->addOption("Title");
$filterdrops->addOption("Owner");
$filterdrops->addOption("Telephone");
$filterdrops->addOption("Date");
*/
//Create a submit button
$filterbutton = new button('filtersearch', "Search");
$filterbutton->setToSubmit();



$textinput = new textinput('filtervalue');
$textinput->size = 17;

$table = &$this->newObject('htmltable', 'htmlelements');
$objDateTime = $this->getObject('dateandtime', 'utilities');
$objDatePicker = $this->newObject('datepicker', 'htmlelements');
$objDatePicker->name = 'startdate';
$objDatePicker2 = $this->newObject('datepicker', 'htmlelements');
$objDatePicker2->name = 'enddate';

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_wicid_startdate', 'wicid', 'Start date') . ": ", "120px", "top", "left");
$table->endRow();
$table->startRow();
$table->addCell($objDatePicker->show(), "190px", "top", "left");
$table->endRow();
$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_wicid_enddate', 'wicid', 'End date') . ": ", "120px", "top", "left");
$table->endRow();
$table->startRow();
$table->addCell($objDatePicker2->show(), "190px", "top", "left");
$table->endRow();
//Radio button Group
$objElement = new radio('filter');
$objElement->addOption('Date',$this->objLanguage->languageText('mod_wicid_searchbydate', 'wicid', 'Date'));
$objElement->addOption('Default',$this->objLanguage->languageText('mod_wicid_otherfields', 'wicid', 'Other fields')."*");
$objElement->setBreakSpace($table->show());
$objElement->setSelected('Default');
$searchbyradio =  $objElement->show();

/*
$ftable->startRow();
$ftable->addCell($searchbyradio);
$ftable->endRow();
 */

$ftable->startRow();
$ftable->addCell($searchbyradio.$textinput->show(), "30px", "top", "left");
$ftable->endRow();

$ftable->startRow();
$ftable->addCell($filterbutton->show());
$ftable->endRow();


//Add a form to contain the search feature
$form = new form('searchdocs', $this->uri(array('action' => 'filterbyparam')));
$form->addToForm($ftable->show());
$form->addToForm("* ".$this->objLanguage->languageText('mod_wicid_searchby', 'wicid', 'Ref No., Title, Owner or Telephone'));
//Add search table to fieldset
$filterset = new fieldset();
$filterset->setLegend($this->objLanguage->languageText('mod_wicid_searchdocsby', 'wicid', 'Search documents by'));
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

$filters = $filterset->show();
$leftColumn .= '<div class="filemanagertree">' . $managenav. $nav . '</div>';

//New Search
$rightColumn = $filters;
$cssLayout->numColumns = 3;
$cssLayout->setLeftColumnContent($leftColumn);
$cssLayout->setMiddleColumnContent($this->getContent());
$cssLayout->setRightColumnContent($rightColumn);
// Display the Layout
echo $cssLayout->show();
?>