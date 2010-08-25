<?php
//Get the CSS layout to make two column layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
//Add some text to the left column
$cssLayout->setLeftColumnContent("Place holder text");
//get the editform object and instantiate it
$objEditForm = $this->getObject('editform', 'helloforms');
//Add the parsed string to the middle (right in two column layout) area
$cssLayout->setMiddleColumnContent($str);
echo $cssLayout->show();
?>

