<?php
/*
 * Template that captures the search parameters
 *
 */
$this->loadclass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('label', 'htmlelements');

$table = &$this->newObject('htmltable', 'htmlelements');

if ($filter == 'Owner') {
    $textinput = new textinput('owner');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>".$this->objLanguage->languageText('mod_wicid_ownername', 'wicid', 'Owner name').": </b>", "120px","top","right");
    $table->addCell($textinput->show(), "30px","top","left");
} elseif ($filter == 'Ref No') {
    $textinput = new textinput('refno');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>".$this->objLanguage->languageText('mod_wicid_refno', 'wicid', 'Ref No').": </b>", "120px","top","right");
    $table->addCell($textinput->show(), "30px","top","left");
} elseif ($filter == 'Telephone') {
    $textinput = new textinput('phoneno');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>".$this->objLanguage->languageText('mod_wicid_telno', 'wicid', 'Telephone No').": </b>", "120px","top","right");
    $table->addCell($textinput->show(), "30px","top","left");
} elseif ($filter == 'Date') {
    $objDateTime = $this->getObject('dateandtime', 'utilities');
    $objDatePicker = $this->newObject('datepicker', 'htmlelements');
    $objDatePicker->name = 'date_from';
    $table->startRow();
    $table->addCell("<b>".$this->objLanguage->languageText('mod_wicid_date', 'wicid', 'Date').": </b>", "120px","top","right");
    $table->addCell($objDatePicker->show(), "190px","top","left");
} elseif ($filter == 'Title') {
    $textinput = new textinput('title');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>".$this->objLanguage->languageText('mod_wicid_doctitle', 'wicid', 'Document title').": </b>", "120px","top","right");
    $table->addCell($textinput->show(), "30px","top","left");
}

//Add the submit button
$button = new button('searchdoc', $this->objLanguage->languageText('mod_wicid_search', 'wicid', 'Search'));
$button->setToSubmit();

//Add button to table
$table->addCell($button->show());
$table->endRow();

//Add form
$form = new form('filterform', $this->uri(array('action' => 'filterbyparam', 'filter' => $filter)));

//Add table to form
$form->addToForm($table->show());

//Add form to fieldset
$filterset = new fieldset();
$filterset->setLegend($this->objLanguage->languageText('mod_wicid_searchdocsby', 'wicid', 'Search Documents By')." <b>".$filter."</b>");
$filterset->addContent($form->show());

echo $filterset->show();
?>