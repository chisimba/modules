<?php
try {
// Create an Instance of the CSS Layout
$cssLayout =& $this->newObject('csslayout', 'htmlelements');

// Create an Instance of the User Side Menu
$sideMenuBar=& $this->getObject('sidemenu','toolbar');
}
catch (customException $e)
{
	customException::cleanUp();
}

// Set the Content of left side column
//    $cssLayout->setLeftColumnContent($sideMenuBar->show('user'));

// Set the Content of middle column
$cssLayout->setMiddleColumnContent($this->getContent());

// Display the Layout
echo $cssLayout->show();

?>
