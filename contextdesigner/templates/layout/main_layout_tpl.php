<?php

if($this->_objDBContext->isInContext())
{
   
    $cm = '';//$this->_objContextUtils->getHiddenContextMenu('contextdesigner','none');
} else {
    $cm = $this->getMenu();
}

// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(3);

// Set the Content of middle column


    $cssLayout->setLeftColumnContent( $this->_objContextAdminUtils->getLeftContent());


$cssLayout->setRightColumnContent( $this->_objContextAdminUtils->getRightContent());
$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show(); 



?>