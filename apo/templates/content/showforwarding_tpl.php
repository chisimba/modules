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



$legend = $document['department'];



$fs = new fieldset();
$fs->setLegend($legend);
//$fs->addContent();



echo $fs->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Forward</b>');


echo $fs->show() . '<br/>';

?>
