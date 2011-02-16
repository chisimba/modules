<?php

/*
 * @category  Chisimba
 * @package   apo (Academic Planning Office)
 * @author    Jacqueline Gil
 */

$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$this->documents = $this->getObject('dbdocuments');

$document = $this->documents->getDocument($id);
$document = unserialize($document);
$action = 'reclaimdocument';

$form = new form('reclaimdocumentform', $this->uri(array('action' => $action, 'id' => $id)));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Reclaim Document');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell("<b>Are you sure you want to reclaim the following document?</b>", 400, "top", null, null, 'colspan = "2"', 1);
$table->endRow();
$table->startRow();
$table->addCell("Ref no:", 100, "top", null, null, null, 0);
$table->addCell($document['refno']);
$table->endRow();
$table->startRow();
$table->addCell("Title:");
$table->addCell($document['docname']);
$table->endRow();
$table->startRow();
$table->addCell("Department:");
$table->addCell($document['department']);
$table->endRow();
$table->startRow();
$table->addCell("Current user:");
$table->addCell($document['currentuserid']);
$table->endRow();
$table->startRow();
$table->addCell("Tel no:");
$table->addCell($document['telephone']);
$table->endRow();
$table->startRow();
$table->addCell("Date:");
$table->addCell($document['date_created']);
$table->endRow();


$fs = new fieldset();
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('yes', $this->objLanguage->languageText('word_yes'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

$button = new button('no', $this->objLanguage->languageText('word_no'));
$uri = $this->uri(array('action' => 'unaaproveddocs'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
