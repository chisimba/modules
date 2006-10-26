<?php


// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(3);

// Set the Content of middle column

//if(isset($txt))

//{

    $cssLayout->setLeftColumnContent($this->getLeftContent());

//}
$cssLayout->setRightColumnContent($this->getRightContent());
$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show(); 



?>