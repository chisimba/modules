<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);

$this->loadClass('htmlheading', 'htmlelements');
$this->objUI    = $this->getObject('ui');
        
$middleColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_aart_enterurl', 'aart');
$header->type = 1;

$middleColumn .= $header->show();

$middleColumn .= $this->objUI->aartform();


$cssLayout->setMiddleColumnContent($middleColumn);

echo $cssLayout->show();
