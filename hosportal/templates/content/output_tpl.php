<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//Get the CSS layout to make two column layout
$cssLayout = $this->newObject('csslayout', 'htmlelements');
//Add some text to the left column
$cssLayout->setLeftColumnContent("Output");
//get the editform object and instantiate it
$objEditForm = $this->getObject('editmessage', 'hosportal');
//Add the parsed string to the middle (right in two column layout) area
$cssLayout->setMiddleColumnContent($str);
echo $cssLayout->show();
?>