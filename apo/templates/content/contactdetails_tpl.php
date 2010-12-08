<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('link', 'htmlelements');


/*$objbackLink = new link($this->uri(array('action'=>'back')));
$objbackLink-> link = 'Back';*/

$form = new form('contactdetailsform');

$table = $this->newObject('htmltable', 'htmlelements');

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'h1';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("H.1. Name of academic proposing the course/unit:");
$table->addCell($editor->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'h2a';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("H.2.a. Name of the School which will be the home for the course/unit:");
$table->addCell($editor->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'h2b';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("H.2.b. School approval signature (Head of School or appropriate School committee chair) and date:");
$table->addCell($editor->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'h3a';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("H.3.a. Telephone contact numbers:");
$table->addCell($editor->show());
$table->endRow();

$editor = $this->newObject('htmlarea', 'htmlelements');
$editor->name = 'h3b';
$editor->height = '70px';
$editor->width = '500px';
$editor->setMCQToolBar();
$table->startRow();
$table->addCell("H.3.b. Email addresses:");
$table->addCell($editor->show());
$table->endRow();

$legend = "<b>H: Contact Details</b>";
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

