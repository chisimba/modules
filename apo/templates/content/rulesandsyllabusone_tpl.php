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
$this->loadClass('textarea', 'htmlelements');

$form = new form('rulesandsyllabusoneform');

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('b1');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.1. How does this course/unit change the rules for the curriculum?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b2');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.2. Describe the course/unit syllabus:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b3a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.3. a. What are the pre-requisites for the course/unit if any?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b3b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.3.b. What are the co-requisites for the course/unit if any?");
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('b4a');
$radio->addOption('1',"a compulsory course/unit");
$radio->addOption('2',"an optional course/unit");
$radio->addOption('3',"both compulsory and optional as the course/unit is offered toward qualifications/ programmes with differing curriculum structures ");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("B.4.a. This is:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('b4b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.4.b. If it is a compulsory course/unit, which course/unit is it replacing, or is the course/unit to be taken by students in addition to the current workload of courses/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b4c');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.4.c. If it is both a compulsory and optional course/unit, provide details explaining for which qualifications/ programmes the course/unit would be optional and for which it would be compulsory:");
$table->addCell($textarea->show());
$table->endRow();

$legend = "<b>B: Rules and Syllabus (page 1)</b>";
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
