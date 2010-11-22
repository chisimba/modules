<?php
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlHeading();
$header->type = "1";
$header->cssClass = "useractivitytitle";
$header->str = $gift['giftname'];

$homelink = new link($this->uri(array()));
$homelink->link = $this->objLanguage->languageText("word_back", "system", "Back");

echo $header->show();

$table = $this->getObject('htmltable', 'htmlelements');

$table->startRow();
$table->addCell("<b>Recepient</b>");
$table->addCell($this->objUser->fullname($gift['recipient']));
$table->endRow();

$table->startRow();
$table->addCell("<b>Description</b>");
$table->addCell($gift['description']);
$table->endRow();

$table->startRow();
$table->addCell("<b>Date Added</b>");
$table->addCell($gift['tran_date']);
$table->endRow();


$table->startRow();
$table->addCell("<b>Donor</b>");
$table->addCell($gift['donor']);
$table->endRow();


$table->startRow();
$table->addCell("<b>Value ZAR</b>");
$table->addCell($gift['value']);
$table->endRow();


$table->startRow();
$table->addCell("<b>Type</b>");
$table->addCell($gift['gift_type']);
$table->endRow();


$table->startRow();
$table->addCell("<b>Department</b>");
$table->addCell($gift['department']);
$table->endRow();


$table->startRow();
$table->addCell("<b>Comments</b>");
$table->addCell($gift['comments']);
$table->endRow();

$fs=new fieldset();
$fs->setLegend("Gift Details");

$fs->addContent( $table->show());
echo $fs->show();
//echo $homelink->show();
?>
