<?php

$this->loadClass('label', 'htmlelements');

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
////$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');


$this->setVar('pageSuppressXML', TRUE);

//$this->loadClass('iframe', 'htmlelements');
//$this->loadClass('button', 'htmlelements');

$table = $this->newObject('htmltable', 'htmlelements');



$legend = "Faculty";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent('<b>'.$document['department'].'</b>');

echo $fs->show() . '<br/>';

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'showeditdocument', 'id'=>$id));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
//$form->addToForm($button->show());

$fs = new fieldset();
$fs->setLegend('Forward');
$fs->addContent($button->show());


echo $fs->show() . '<br/>';

?>
