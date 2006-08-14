<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_beautifier_heading', 'beautifier');

echo $header->show();

$themod = $this->getParam('mod');

$intro = $this->objLanguage->languageText('mod_beautifier_beautifying', 'beautifier');
$tail = $this->objLanguage->languageText('mod_beautifier_finished', 'beautifier');
$complete = $this->objLanguage->languageText('mod_beautifier_completemess', 'beautifier');

echo $intro . "  " . $themod . "  " . $complete;
?>