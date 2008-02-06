<?php
//Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent("This is a work in progress");
$objWidjet = $this->getObject("tweetbox","twitter");
$cssLayout->setMiddleColumnContent($objWidjet->show());
echo $cssLayout->show();
?>