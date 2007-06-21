<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('button', 'htmlelements');




    echo '<h1>'.$this->objLanguage->languageText('mod_contextcontent_contextpagesfor','contextcontent')." ".$this->objContext->getTitle().' - '.$this->objLanguage->languageText('mod_contextcontent_createachapter','contextcontent').'</h1>';
    
    echo '<p>'.$this->objLanguage->languageText('mod_contextcontent_createchapterexplanation','contextcontent').'</p>';
    
$form = new form ('addchapter', $this->uri(array('action'=>'savechapter')));
$table = $this->newObject('htmltable', 'htmlelements');

$title = new textinput('chapter');
$title->size = 60;
$label = new label ($this->objLanguage->languageText('mod_contextcontent_chaptertitle','contextcontent'), 'input_chapter');
$table->startRow();
$table->addCell($label->show());
$table->addCell($title->show());
$table->endRow();

$label = new label ($this->objLanguage->languageText('mod_contextcontent_aboutchapter_introduction','contextcontent'), 'input_aboutchapter');
$htmlArea = $this->newObject('htmlarea', 'htmlelements');
$htmlArea->name = 'intro';
$table->startRow();
$table->addCell($label->show());
$table->addCell($htmlArea->show());
$table->endRow();


$radio = new radio ('visibility');
$radio->addOption('Y', ' '.$this->objLanguage->languageText('word_yes','system', 'Yes'));
$radio->addOption('N', ' '.$this->objLanguage->languageText('word_no','system', 'No'));
$radio->addOption('I', ' '.$this->objLanguage->languageText('mod_contextcontent_onlyshowintroduction','contextcontent'));
$radio->setSelected('Y');
$radio->setBreakSpace(' &nbsp; ');

$table->startRow();
$table->addCell($this->objLanguage->code2Txt('mod_contextcontent_visibletostudents','contextcontent'));
$table->addCell($radio->show());
$table->endRow();


$form->addToForm($table->show());


$button = new button('submitbutton', $this->objLanguage->languageText('mod_contextcontent_chapter','contextcontent'));
$button->setToSubmit();
$form->addToForm($button->show());

//$form->addRule('chapter');

echo $form->show();

?>