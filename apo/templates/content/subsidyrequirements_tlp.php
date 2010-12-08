<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');




$button = new button('save', $this->objLanguage->languageText('mod_wicid_save', 'wicid', 'Save Document'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array());
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
