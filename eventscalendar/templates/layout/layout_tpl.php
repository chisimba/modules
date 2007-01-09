<?php



// Create an Instance of the CSS Layout

$cssLayout =& $this->newObject('csslayout', 'htmlelements');

  $cssLayout->setNumColumns(2);

// Set the Content of middle column

//create the context menu if you are in a context
if($this->_objDBContext->isInContext())
{
    $objContextUtils = & $this->getObject('utilities','context');
    $cm = $objContextUtils->getHiddenContextMenu();
} else {
    $cm = '';
}


$cssLayout->setLeftColumnContent($this->getMenu().$cm);


$cssLayout->setMiddleColumnContent($this->getContent());



// Display the Layout

echo $cssLayout->show(); 



?>