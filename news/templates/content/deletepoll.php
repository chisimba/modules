<?php

/* FIX IN CONTROLLER */


$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

echo '<h1>Delete Album: '.$album['albumname'].'</h1>';

echo '<p>Are you sure you want to delete this album?</p>';

$form = new form('deletealbum', $this->uri(array('action'=>'deletealbumconfirm')));

$hiddeninput = new hiddeninput('id', $id);
$form->addToForm($hiddeninput->show());

$hiddeninput = new hiddeninput('random', $random);
$form->addToForm($hiddeninput->show());


$radio = new radio ('confirm');
$radio->addOption('no', 'No - do not delete the album');
$radio->addOption('yes', 'Yes - Delete the album');
$radio->setSelected('no');
$radio->setBreakSpace('<br />');

$form->addToForm($radio->show());

$button = new button ('submitform', 'Submit');
$button->setToSubmit();

$form->addToForm('<p>'.$button->show().'</p>');

echo $form->show();

$objHighlightLabels = $this->getObject('highlightlabels', 'htmlelements');
echo $objHighlightLabels->show();

?>