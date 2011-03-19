<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(1);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
$this->objWallOps    = $this->getObject('wallops', 'wall'); 
        
$middleColumn = NULL;
$leftColumn = NULL;

$middleColumn .= "photo thing";
$middleColumn .= $this->objWallOps->showWall(0, 10);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
