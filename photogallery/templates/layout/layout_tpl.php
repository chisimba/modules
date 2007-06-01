
<?php
// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(2);

// Set the Content of middle column


    $cssLayout->setLeftColumnContent($this->_objUtils->getNav());


//$cssLayout->setRightColumnContent($this->getRightWidgets());
$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout
if($this->_objUser->isLoggedIn())
{
	echo $cssLayout->show();	
} else {
	echo '<div id="main">'.$this->getContent().'</div>';
}


?>