<?php

// Create an Instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
  $cssLayout->setNumColumns(2);
// Set the Content of middle column
//if(isset($txt))
//{
    $cssLayout->setLeftColumnContent($this->getMenu());
//}
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show(); 

?>