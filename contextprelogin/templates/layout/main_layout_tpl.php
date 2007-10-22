<?php


// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(3);

// Set the Content of middle column


    $cssLayout->setLeftColumnContent($this->_objUtils->getLeftContent());


$rightContent = $this->_objUtils->getRightContent();

if ($rightContent == '') {
    $cssLayout->setNumColumns(2);
} else {
    $cssLayout->setRightColumnContent($rightContent);
}


$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show();



?>