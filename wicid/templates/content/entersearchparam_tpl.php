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
$this->loadClass('hiddeninput', 'htmlelements');

$table = &$this->newObject('htmltable', 'htmlelements');

if ($filter == 'Owner') {
    $textinput = new textinput('filtervalue');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_ownername', 'wicid', 'Owner name') . ": </b>", "120px", "top", "right");
    $table->addCell($textinput->show(), "30px", "top", "left");
} elseif ($filter == 'Ref No') {
    $textinput = new textinput('filtervalue');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_refno', 'wicid', 'Ref No') . ": </b>", "120px", "top", "right");
    $table->addCell($textinput->show(), "30px", "top", "left");
} elseif ($filter == 'Telephone') {
    $textinput = new textinput('filtervalue');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_telno', 'wicid', 'Telephone No') . ": </b>", "120px", "top", "right");
    $table->addCell($textinput->show(), "30px", "top", "left");
} elseif ($filter == 'Date') {
    $objDateTime = $this->getObject('dateandtime', 'utilities');
    $objDatePicker = $this->newObject('datepicker', 'htmlelements');
    $objDatePicker->name = 'filtervalue';
    $table->startRow();
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_date', 'wicid', 'Date') . ": </b>", "120px", "top", "right");
    $table->addCell($objDatePicker->show(), "190px", "top", "left");
} elseif ($filter == 'Title') {
    $textinput = new textinput('filtervalue');
    $textinput->size = 30;
    $table->startRow();
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_doctitle', 'wicid', 'Document title') . ": </b>", "120px", "top", "right");
    $table->addCell($textinput->show(), "30px", "top", "left");
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
$filterset->setLegend($this->objLanguage->languageText('mod_wicid_searchdocsby', 'wicid', 'Search Documents By') . " <b>" . $filter . "</b>");
$filterset->addContent($form->show());

echo $filterset->show();
//Add table to show results
$table = &$this->newObject('htmltable', 'htmlelements');
//Display results if search was positive
if (count($files) >= 1) {

    $table->startRow();
    //$table->addCell("<b>Select</b>");
    $table->addCell("<b>Type</b>");
    $table->addCell("<b>Title</b>");
    $table->addCell("<b>Ref No</b>");
    $table->addCell("<b>Owner</b>");
    $table->endRow();
    foreach ($files as $file) {
        $dlink1 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
        $dlink1->link = $file['thumbnailpath'];

        $dlink2 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
        $dlink2->link = $file['actualfilename'];
        //Get the document Id
        $docId = $this->documents->getIdWithRefNo($file['refno']);

        //Create checkbox to help select record for batch execution
        $approve = &new checkBox($docId . '_app', Null, Null);
        $approve->setValue('execute');

        $table->startRow();
        //$table->addCell($approve->show());
        $table->addCell($dlink1->show());
        $table->addCell($dlink2->show());
        $table->addCell($file['refno']);
        $table->addCell($file['owner'] . '(' . $file['telephone'] . ')');
        $table->endRow();
    }
    //Create legend for the search results
    $fs = new fieldset();
    $fs->setLegend('Search Results');
    $fs->addContent($table->show());

    echo $fs->show();
} elseif($status == 1) {
    $table->startRow();
    $table->addCell("There are no records found");
    $table->endRow();
    //Create legend for the search results
    $fs = new fieldset();
    $fs->setLegend('Search Results');
    $fs->addContent($table->show());

    echo $fs->show();    
}
?>