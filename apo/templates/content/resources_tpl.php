<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$form = new form('resourcesform');

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('e1a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.1.a. Is there currently adequate teaching capacity with regard to the introduction of the course/unit? ");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e1b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.1.b. Who will teach the course/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.2.a. How many students will the course/unit attract?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.2.b. How has this been factored into the enrolment planning in your Faculty?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2c');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.2.c. How has it been determined if the course/unit is sustainable in the long term, or short term if of topical interest?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.3.a. Specify the space requirements for the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.3.b. Specify the IT teaching resources required for the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3c');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.3.c. Specify the library resources required to teach the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e4');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.4. Does the School intend to offer the course/unit in addition to its current course/unit offerings, or is the intention to eliminate an existing course/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e5a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.5.a. Specify the name of the course/unit co-ordinator:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e5b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("E.5.b. State the Staff number of the course/unit coordinator (consult your Faculty Registrar):");
$table->addCell($textarea->show());
$table->endRow();

$legend = "<b>E: Resources</b>";
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
