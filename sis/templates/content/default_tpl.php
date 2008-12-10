<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('usermenu', 'toolbar');
$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objSF = $this->getObject('sisforms');
$objheader = $this->getObject('htmlheading', 'htmlelements');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL;
$leftCol = NULL;
$middleColumn = NULL;
$rightSideColumn = $objSF->parentMenu(TRUE);

$leftCol .= $objSideBar->show();
$tomsg = $this->getObject('timeoutmessage', 'htmlelements');
$tomsg->message = $message;
$middleColumn .=  $tomsg->show()."<br />";

$middleColumn .= $objSF->countKids($children);
$details = $objSF->listKids($children);
foreach ($details as $kids) {
    $name = $kids[0];
    // make into a link
    $nlink = new href ( $this->uri ( array ('action' => 'viewstudent', 'recid' => $kids[1]) ), $name );
    $middleColumn .= $nlink->show()."<br />";
}


$objheader->str = $this->objLanguage->languageText("mod_sis_filesheader", "sis");
$objheader->type = 1;
$middleColumn .= $objheader->show();
$middleColumn .= $objSF->listFiles();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>