<?php
//write post template

//initiate objects
$this->loadClass('label', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('form', 'htmlelements');
//$this->loadClass('heading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->loadClass('htmlarea', 'htmlelements');


$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objSideBar = $this->newObject('sidebar', 'navigation');

// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = &$this->newObject('usermenu', 'toolbar');
$rightSideColumn = NULL;
$leftCol = NULL;
$middleColumn = NULL;

//left menu section
$leftCol = $leftMenu->show();
//$leftCol .= $this->objblogOps->geoTagForm();

//get the posts editor
$middleColumn = $this->objblogOps->postEditor($userid, NULL);
//$middleColumn .= $this->objblogOps->geoTagForm();
$rightSideColumn .= $this->objblogOps->quickCats(TRUE);
$rightSideColumn .= $this->objblogOps->showAdminSection(TRUE);//quickPost($userid, TRUE);
$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol);
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>