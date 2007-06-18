<?php


// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(3);

// Set the Content of middle column


    $cssLayout->setLeftColumnContent($this->_objUtils->getLeftContent());


$cssLayout->setRightColumnContent(/*$this->_objUtils->getRightContent()*/" ");
$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show(); 



?>
