<?php

// Create an Instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

$cssLayout->setLeftColumnContent('');

// Set the Content of middle column
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>