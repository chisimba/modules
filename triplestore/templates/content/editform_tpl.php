<?php

$document = new DOMDocument('1.0');
$html5form = $this->getObject('html5form', 'html5elements');
$form = $html5form->form($document, 'POST');
$document->appendChild($form);

$fields = array();

foreach (array('subject'=>'Subject', 'predicate'=>'Predicate', 'object'=>'Ohject') as $id => $caption) {
    $p = $document->createElement('p');
    $p->appendChild($html5form->label($document, $id, $caption.': '));
    $p->appendChild($html5form->text($document, $id));
    $form->appendChild($p);
}

$p = $document->createElement('p');
$p->appendChild($html5form->submit($document, 'Add'));
$form->appendChild($p);

echo $document->saveHTML();
