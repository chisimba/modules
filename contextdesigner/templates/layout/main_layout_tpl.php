<?php

if($this->_objDBContext->isInContext())
{
   
    $cm = '';//$this->_objContextUtils->getHiddenContextMenu('contextdesigner','none');
} else {
    $cm = '';
}

// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(3);

// Set the Content of middle column

$objU = $this->getObject('utils', 'contextadmin');
    $cssLayout->setLeftColumnContent( $objU->getLeftContent());




$cssLayout->setRightColumnContent($objU->getRightContent());
$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show(); 



?>