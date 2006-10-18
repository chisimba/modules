<?php

// Create an Instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

// Create an Instance of the User Side Menu
$userMenuBar=& $this->getObject('sidemenu','toolbar');

// Set the Content of left side column
$cssLayout->setLeftColumnContent($userMenuBar->menuUser());

// Set the Content of middle column
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show(); 

?>
