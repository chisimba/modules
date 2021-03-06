<?php 

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('link', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->cssClass = 'warning';
$header->str = $this->objLanguage->languageText('mod_hotels_confirmdeletestory', 'hotels', 'Are you sure you want to delete this hotel?');

echo $header->show();

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('word_title', 'system', 'Title').':</strong>', 100);

$storyLink = new link ($this->uri(array('action'=>'viewstory', 'id'=>$story['storyid'])));
$storyLink->link = $story['storytitle'];
$table->addCell($storyLink->show());
$table->endRow();
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('mod_hotels_storydate', 'hotels', 'Story Date').':</strong>');
$table->addCell($story['storydate']);
$table->endRow();
$table->startRow();
$table->addCell('<strong>'.$this->objLanguage->languageText('word_category', 'system', 'Category').':</strong>');
$table->addCell($story['categoryname']);
$table->endRow();

if ($story['location'] != '') {
    $table->startRow();
    $table->addCell('<strong>'.$this->objLanguage->languageText('mod_prelogin_location', 'prelogin', 'Location').':</strong>');
    $table->addCell($story['location']);
    $table->endRow();
}

echo $table->show();

//echo 'Add Num Comments';

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

$form = new form ('deletestoryconfirm', $this->uri(array('action'=>'deletestoryconfirm')));

$radio = new radio ('confirm');
$radio->addOption('no', $this->objLanguage->languageText('mod_hotels_nodeletestory', 'hotels', 'No - Do not delete this story'));
$radio->addOption('yes', $this->objLanguage->languageText('mod_hotels_yesdeletestory', 'hotels', 'Yes - Delete this Story'));
$radio->setBreakSpace(' &nbsp; / &nbsp; ');
$radio->setSelected('no');

$form->addToForm('<p>&nbsp;</p><p align="center">'.$radio->show().'</p>');

$button = new button ('confirmbutton', $this->objLanguage->languageText('mod_hotels_confirmaction', 'hotels', 'Confirm Action'));
$button->setToSubmit();

$form->addToForm('<p align="center">'.$button->show().'</p>');

$hiddenInput = new hiddeninput('id', $story['id']);
$form->addToForm($hiddenInput->show());

$hiddenInput = new hiddeninput('deletevalue', $deleteValue);
$form->addToForm($hiddenInput->show());

echo $form->show();

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_hotels_storytext', 'hotels', 'Story Text');

echo $header->show();

echo $story['storytext'];

?>