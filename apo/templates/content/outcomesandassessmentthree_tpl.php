<?php

$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');


$form = new form('outcomesandassessmentthreeform');

$table = $this->newObject('htmltable', 'htmlelements');

$table->startRow();
$table->addCell("<b><i>D.5. Specify the notional study hours expected for the duration of the course/unit using the spreadsheet provided.</b></i>");
$table->endRow();

$textinput = new textinput('a');
$textinput->size = 10;
$table->startRow();
$table->addCell("a. Over how many weeks will this course run?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('b');
$textinput->size = 10;
$table->startRow();
$table->addCell("b. How many hours of teaching will a particular student experience for this specific course in a single week?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('c');
$textinput->size = 10;
$table->startRow();
$table->addCell("c. How many hours of tutorials will a particular student experience for this specific course in a single week?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('d');
$textinput->size = 10;
$table->startRow();
$table->addCell("d. How many lab hours will a particular student experience for this specific course in a single week? (Note: the assumption is that there is only one staff contact hour per lab, the remaining lab time is student self-study)");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('e');
$textinput->size = 10;
$table->startRow();
$table->addCell("e. How many other contact sessions are there each week including periods used for testd or other assessments which have not been included in the number of lecture, tutorial or laboratory sessions.");
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>Total contact time</b>");
$table->addCell("<b>123</b>");
$table->endRow();

$textinput = new textinput('f');
$textinput->size = 10;
$table->startRow();
$table->addCell("f. For every hour of lectures or contact with a staff member, how many hours should the student spend studying by her/himself?");
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>Total notional study hours (excluding the exams)</b>");
$table->addCell("<b>123</b>");
$table->endRow();

$textinput = new textinput('g');
$textinput->size = 10;
$table->startRow();
$table->addCell("g. How many exams are there per year?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('h');
$textinput->size = 10;
$table->startRow();
$table->addCell("h. How long is each exam?");
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>Total exam time per year</b>");
$table->addCell("<b>123</b>");
$table->endRow();

$textinput = new textinput('i');
$textinput->size = 10;
$table->startRow();
$table->addCell("i. How many hours of preparation for the exams is the student expected to undertake?");
$table->addCell($textinput->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>Total notional study hours</b>");
$table->addCell("<b>123</b>");
$table->endRow();

$table->startRow();
$table->addCell("<b>Total SAQA Credits</b>");
$table->addCell("<b>123</b>");
$table->endRow();

$legend = "<b>D: Outcomes and Assessment (page 3)</b>";
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

/*controller :
        if (!is_numeric($value)) {
            $errormessages[] = "Value must be integer";
        }
        */