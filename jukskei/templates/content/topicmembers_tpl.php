<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$sessionmanager= $this->getObject("contentmanager");

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);
//Add the table to the centered layer
$rightSideColumn .=  $sessionmanager->showTopicMembersList($topicid);

$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent( $postLoginMenu->show());

// Add Right Column
$cssLayout->setMiddleColumnContent( $rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
?>
