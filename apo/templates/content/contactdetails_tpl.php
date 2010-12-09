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
$this->loadClass('textarea', 'htmlelements');


/*$objbackLink = new link($this->uri(array('action'=>'back')));
$objbackLink-> link = 'Back';*/

$form = new form('contactdetailsform');

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('h1');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("H.1. Name of academic proposing the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h2a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("H.2.a. Name of the School which will be the home for the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h2b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("H.2.b. School approval signature (Head of School or appropriate School committee chair) and date:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h3a');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("H.3.a. Telephone contact numbers:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h3b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("H.3.b. Email addresses:");
$table->addCell($textarea->show());
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

