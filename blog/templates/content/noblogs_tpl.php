<?php
//no blogs available for show...so we redirect to the login page again...
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$middleColumn = NULL;
$objUi = $this->getObject('blogui');
// left hand blocks
$leftCol = $objUi->leftBlocks($userid);
// right side blocks
$rightSideColumn = $objUi->rightBlocks($userid, NULL);
$middleColumn.= "<h1><em><center>" . $this->objLanguage->languageText("mod_blog_noblogs", "blog") . "</center></em></h1>";
$middleColumn.= "<br />";
//$homelink = new href($this->uri(NULL, '_default'), $this->objLanguage->languageText("mod_blog_loginhere", "blog"));
//$middleColumn .= "<center>" .$homelink->show() . "</center>";
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>