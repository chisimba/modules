<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section C: Subsidy Requirements');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();


/* if ($mode == 'edit') {
  $legend = "Edit document";
  } */

//opening Section C.1.
$textinput = new textinput('c1');
$textinput->size = 60;
$textinput->value = $instructionmode;

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell('<b>C.1. The mode of instruction is understood to be contact/face-to-face lecturing. Provide details if any other mode of delivery is to be used:</b>');
$table->addCell($textinput->show());
$table->endRow();

//opening Section C.2.
$courseTought = new dropdown('number');
$courseTought->addOption("off-campus");
$courseTought->addOption("on-campus");

if ($mode == 'fixup') {
    $courseTought->setSelected($campusOption);
}
if ($mode == 'edit') {
    $courseTought->setSelected(substr($document['refno'], 0, 1));
}
$table->startRow();
$table->addCell("<b>C.2.a. The course/unit is taught:</b>");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($courseTought->show());
}
$table->endRow();

$textinput = new textinput('unitTought');
$textinput->size = 60;
$textinput->value = $unitTought;

$table->startRow();
$table->addCell('<b>C.2.b. If the course/unit is taught off-campus provide details:</b>');
$table->addCell($textinput->show());
$table->endRow();

//Section C.3.

$textinput = new textinput('unitDetails');
$textinput->size = 60;
$textinput->value = $unitDetails;

$table->startRow();
$table->addCell('<b>C.3. What is the third order CESM (Classification of Education SUbject Matter) category for the course/unit? (The CESM manual can be downloaded from http://intranet.wits.ac.za/Academic/APO/CESMs.htm):</b>');
$table->addCell($textinput->show());
$table->endRow();

//Section C.4.
$otherEntity = new dropdown('entity');
$otherEntity->addOption("Yes");
$otherEntity->addOption("No");

if ($mode == 'fixup') {
    $otherEntity->setSelected($entityOption);
}
if ($mode == 'edit') {
    $courseTought->setSelected(substr($document['refno'], 0, 1));
}
$table->startRow();
$table->addCell("<b>C.4.a. Is any other School/Entity involved in teaching this unit?:</b>");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($otherEntity->show());
}
$table->endRow();

$textarea = new textarea('otherEntity');
$textarea->size = 60;
$textarea->value = $entityDetails;

$table->startRow();
$table->addCell('<b>C.4.b. If yes, state the name of the School/Entity:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('percentageEntity');
$textarea->size = 60;
$textarea->value = $percentageDetails;

$table->startRow();
$table->addCell('<b>Percentage each teaches.:</b>');
$table->addCell($textarea->show());
$table->endRow();

$legend = "Subsidy Requirements";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
//echo $fs->show();
$form = new form('subsidyrequirementsform', $this->uri(array('action' => $action)));

$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

$form->addToForm($fs->show());

$button = new button('save', $this->objLanguage->languageText('mod_wicid_save', 'wicid', 'Save Document'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

echo $form->show();
?>
