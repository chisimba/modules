<?php

$document = new DOMDocument('1.0');

$html5form = $this->getObject('html5form', 'html5elements');
$html5table = $this->getObject('html5table', 'html5elements');

$form = $html5form->form($document, 'POST', array('action'=>'search'), 'triplestore');
$document->appendChild($form);

$p = $document->createElement('p');
$form->appendChild($p);

$headers = array();
foreach (array('id', 'subject', 'predicate', 'object') as $field) {
    $header = $this->objLanguage->languageText('mod_triplestore_'.$field, 'triplestore');
    $p->appendChild($html5form->text($document, $field, NULL, $header));
    $headers[] = $header;
}

$title = $this->objLanguage->languageText('mod_triplestore_triples', 'triplestore');

$table = $html5table->table($document, $title, $headers, $this->triples);
$document->appendChild($table);

echo $document->saveHTML();
