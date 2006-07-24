<?php

// Create an Instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

// Create an Instance of the User Side Menu
$sideMenuBar=& $this->getObject('sidemenu','toolbar');

$objContext =& $this->getObject('dbcontext', 'context');

// Set the Content of left side column
if ($objContext->getContextCode() == '') {
    $cssLayout->setLeftColumnContent($sideMenuBar->show('user'));
} else {
    $cssLayout->setLeftColumnContent($sideMenuBar->show('context'));
}

// Set the Content of middle column
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>