<?php

$objGeshi = & $this->getObject('geshiwrapper');
//Language and source must be specified before startGeshi
$objGeshi->language = $this->getParam('language', 'php');
$objGeshi->source = stripslashes($this->getParam('code', NULL));
//Start the geshi object
$objGeshi->startGeshi();
//Specific geshi settings must come after startGeshi
$objGeshi->enableLineNumbers();
$line=$this->getParam('line', 1);
$objGeshi->startLineNumbersAt($line);
//Render the output
echo $objGeshi->show();


?>