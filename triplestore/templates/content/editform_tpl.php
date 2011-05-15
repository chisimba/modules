<?php

$document = new DOMDocument('1.0');
$html5form = $this->getObject('html5form', 'html5elements');
$form = $html5form->form($document, 'POST');
$document->appendChild($form);

foreach (array('subject', 'predicate', 'object') as $field) {
    $caption = $this->objLanguage->languageText('mod_triplestore_'.$field, 'triplestore');
    $p = $document->createElement('p');
    $p->appendChild($html5form->label($document, $field, $caption.': '));
    $p->appendChild($html5form->text($document, $field));
    $form->appendChild($p);
}

$p = $document->createElement('p');
$p->appendChild($html5form->submit($document, $this->objLanguage->languageText('mod_triplestore_add', 'triplestore')));
$form->appendChild($p);

echo $document->saveHTML();
