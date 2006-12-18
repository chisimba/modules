<?php

// Create an instance of the CSS Layout
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
// Set the Content of middle column
$cssLayout->setLeftColumnContent($this->getCMSMenu());
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>