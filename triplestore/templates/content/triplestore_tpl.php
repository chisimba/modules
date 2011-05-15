<?php

$document = new DOMDocument('1.0');

$html5form = $this->getObject('html5form', 'html5elements');
$html5table = $this->getObject('html5table', 'html5elements');

$form = $html5form->form($document, 'POST', array('action'=>'search'), 'triplestore');
$document->appendChild($form);

$p = $document->createElement('p');
$form->appendChild($p);

$p->appendChild($html5form->text($document, 'id', NULL, 'Identifier'));
$p->appendChild($html5form->text($document, 'subject', NULL, 'Subject'));
$p->appendChild($html5form->text($document, 'predicate', NULL, 'Predicate'));
$p->appendChild($html5form->text($document, 'object', NULL, 'Object'));
$p->appendChild($html5form->submit($document, 'Search'));

$title = 'Triples';
$headers = array('Identifier', 'Subject', 'Predicate', 'Object');
var_dump($this->triples);

$table = $html5table->table($document, $title, $headers, $this->triples);
$document->appendChild($table);

echo $document->saveHTML();
