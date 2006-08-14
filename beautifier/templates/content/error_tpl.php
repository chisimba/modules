<?php

$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('dropdown','htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_beautifier_heading', 'beautifier');

$instructions = new htmlheading();
$instructions->type = 3;
$instructions->str = $this->objLanguage->languageText('mod_beautifier_instructions', 'beautifier');

$header->show();

$modarr = array();

//echo $header->show();

$dd = & new dropdown('mod');
$dd->addFromDB($modarr);
$dd->show();

?>