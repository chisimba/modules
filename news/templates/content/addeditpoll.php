<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_addnewpoll', 'news', 'Add New Poll');

echo $header->show();

$form = new form ('savepoll', $this->uri(array('action'=>'savenewpoll')));

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell($this->objLanguage->languageText('word_question', 'word', 'Question').':');
$question = new textarea ('question');
$table->addCell($question->show());
$table->endRow();

$table->startRow();
$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();

for ($i=1; $i<=3; $i++)
{
    $table->startRow();
    $table->addCell($this->objLanguage->languageText('mod_word_option', 'word', 'Option').' '.$i.':');
    $option = new textinput ('option'.$i);
    $option->size = 60;
    $table->addCell($option->show());
    $table->endRow();
}

$table->startRow();
$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();

$button = new button ('savepoll', $this->objLanguage->languageText('phrase_savepoll', 'phrase', 'Save Poll'));
$button->setToSubmit();

$table->startRow();
$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();

$form->addToForm($table->show());

echo $form->show();
?>