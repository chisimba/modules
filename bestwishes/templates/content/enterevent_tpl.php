<?php

/**
 * @ Author Emmanuel Natalis
 * @ University of dar es salaam
 * @ 2008
 */
$this->objLanguage = $this->getObject('language', 'language');
$this->objbestWishesForms = $this->getObject('bestwishesforms', 'bestwishes');
$this->objHtmltable = $this->getObject('htmltable', 'htmlelements');
$this->objHtmltable->startRow();
$this->objHtmltable->addCell("", 200);
$this->objHtmltable->addCell("<h3>" . $this->objLanguage->languageText('mod_bestwishes_eventheading', 'bestwishes') . "</h3><br>" . $this->objbestWishesForms->buildFormEnterEvent(), 300);
$this->objHtmltable->addCell("", 200);
echo $this->objHtmltable->show();
?>
