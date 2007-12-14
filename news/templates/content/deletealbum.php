<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_news_deletealbum', 'news', 'Delete Album').': '.$album['albumname'];

echo $header->show();


echo '<p>'.$this->objLanguage->languageText('mod_news_confirmdeletealbum', 'news', 'Are you sure you want to delete this album?').'</p>';

$form = new form('deletealbum', $this->uri(array('action'=>'deletealbumconfirm')));

$hiddeninput = new hiddeninput('id', $id);
$form->addToForm($hiddeninput->show());

$hiddeninput = new hiddeninput('random', $random);
$form->addToForm($hiddeninput->show());


$radio = new radio ('confirm');
$radio->addOption('no', $this->objLanguage->languageText('mod_news_nodeletealbum', 'news', 'No - do not delete the album'));
$radio->addOption('yes', $this->objLanguage->languageText('mod_news_yesdeletealbum', 'news', 'Yes - Delete the album'));
$radio->setSelected('no');
$radio->setBreakSpace('<br />');

$form->addToForm($radio->show());

$button = new button ('submitform', $this->objLanguage->languageText('word_submit', 'word', 'Submit'));
$button->setToSubmit();

$form->addToForm('<p>'.$button->show().'</p>');

echo $form->show();

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

?>