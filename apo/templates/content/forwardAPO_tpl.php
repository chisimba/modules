<?php


$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);


$action = 'showreview';
$form = new form('forwardtoAPOform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'forwardtoAPO')));

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Forward to APO');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

//$legend = "<b>Forward Document to APO</b>";

$legend = "Faculty";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent("Engineering");

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

/*$table->startRow();
$table->boarder='1';
$table->addCell('Current editor:&nbsp;' . $this->objUser->fullname($document['currentuserid']));
$table->endRow();*/

$table->startRow();
$table->width="50%";
$table->addCell("Name:  ");
$table->addCell("Palesa Mokwena");
$table->endRow();

$table->startRow();
$table->width="50%";
$table->addCell("email adress:  ");
$table->addCell("pmokwena@gmail.com");
$table->endRow();

$table->startRow();
$table->width="50%";
$table->addCell("Telephone Number:  ");
$table->addCell("011 717 7183".'&nbsp');
$table->endRow();

$legend = "Foward to APO";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm('<br/>' .$button->show().'&nbsp');
$form->extra = 'class="sections"';

$forwardText = $this->objLanguage->languageText('mod_apo_wicid', 'wicid', 'Send to APO');

$button = new button('forward', $forwardText);
$uri = $this->uri(array('action'=>'forwardDocAPO', 'from' => 'home_tpl.php', 'id' => $id, 'mode'=> $mode));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>