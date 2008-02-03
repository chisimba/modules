<?php
//Create an instance of the css layout class
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($leftPanel);
$cssLayout->setMiddleColumnContent($codeEditor);
$cssLayout->setRightColumnContent($rightPanel);
echo $cssLayout->show();
?>