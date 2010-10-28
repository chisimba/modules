<?php

$objForm = $this->getObject('html5form', 'html5elements');
$objTable = $this->getObject('html5table', 'html5elements');

$document = new DOMDocument();

$form = $objForm->form($document, 'GET');
$document->appendChild($form);

$form->appendChild($objForm->hidden($document, 'module', 'test'));
$form->appendChild($objForm->hidden($document, 'action', 'search'));
$form->appendChild($objForm->text($document, 'q', 'Enter your query', NULL, TRUE, TRUE, TRUE));
$form->appendChild($objForm->submit($document, 'Search'));

$title = 'Test';
$headers = array('Column 1', 'Column 2', 'Column 3');
$contents = array(array('One', 'Two', 'Three'), array('Four', 'Five', 'Six'));
$edit = array('module' => 'test', 'action' => 'edit');
$delete = array('module' => 'test', 'action' => 'delete');
$module = 'test';
$checkbox = 'test';

$form->appendChild($objTable->table($document, $title, $headers, $contents, $edit, $delete, $module, $checkbox));

echo $document->saveHTML();
