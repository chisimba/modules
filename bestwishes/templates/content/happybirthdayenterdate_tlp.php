<?php

/**
 * @ Author Emmanuel Natalis
 * @ Open Source Software Developer
 * @ University Computing center
 * @ University of dar es salaam
 *
 *
 */
$this->objHappybirthday = $this->getObject('happybirthday', 'bestwishes');
echo $this->objHappybirthday->goBack();
$this->objHtmltable = $this->getObject('htmltable', 'htmlelements');
$this->content = $this->objHappybirthday->displayWelcMsg() . "<br>" . $this->objHappybirthday->buildForm();
$this->objHtmltable->startRow();
$this->objHtmltable->addCell(null, 200);
$this->objHtmltable->addCell($this->content, 400);
$this->objHtmltable->addCell(null, 200);
$this->objHtmltable->endRow();
echo $this->objHtmltable->show();
?>