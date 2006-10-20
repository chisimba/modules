<?php

//random blog template

$this->loadClass('href', 'htmlelements');
$tt = $this->newObject('domtt', 'htmlelements');
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
//$objSideBar = $this->newObject('sidebar', 'navigation');
// Set columns to 3
$cssLayout->setNumColumns(3);
$leftMenu = NULL; //&$this->newObject('usermenu', 'toolbar');

$rightSideColumn = NULL; //$this->objLanguage->languageText('mod_blog_instructions', 'blog');

//set up a link to the other users blogs...
$oblogs = new href($this->uri(array('action' => 'allblogs')),$this->objLanguage->languageText("mod_blog_viewallblogs", "blog"), NULL);
$rightSideColumn .= $oblogs->show();
$leftCol = NULL;
$middleColumn = NULL;







$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftCol); //$leftMenu->show());
$cssLayout->setRightColumnContent($rightSideColumn);
echo $cssLayout->show();
?>