<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');


$form = new form('overviewform');

$table = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('a1');
$textinput->size = 50;
$table->startRow();
$table->addCell("A.1. Name of course/unit:");
$table->addCell($textinput->show());
$table->endRow();

$radio = new radio ('a2');
$radio->addOption('1',"proposal for a new course/unit ");
$radio->addOption('2',"change to the outcomes or credit value of a course/unit");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("A.2. This is a:");
$table->addCell($radio->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'a3';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("A.3. Provide a brief motivation for the introduction/ amendment of the course/unit:");
$table->addCell($editor->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'a4';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("A.4. Towards which qualification(s) can the course/unit be taken?");
$table->addCell($editor->show());
$table->endRow();

$radio = new radio ('a5');
$radio->addOption('1',"linked to other recent course/unit proposal/s, or proposal/s currently in development");
$radio->addOption('2',"linked to other recent course/unit amendment/s, or amendment/s currently in development");
$radio->addOption('3',"linked to a new qualification/ programme proposal, or one currently in development");
$radio->addOption('4',"linked to a recent qualification/ programme amendment, or one currently in development");
$radio->addOption('5',"not linked to any other recent academic developments, nor those currently in development");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("A.5. This new or amended course proposal is:");
$table->addCell($radio->show());
$table->endRow();

$legend = "<b>A: Overview</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array());
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array());
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array());
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
