<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
// $this->objWallOps    = $this->getObject('wallops', 'wall'); 
        
$middleColumn = NULL;
$leftColumn = NULL;

// send the data from controller to an operation function to build the gallery now
$middleColumn .= $this->objOps->formatData($data);

//$middleColumn .= $this->objWallOps->showWall(0, 10);

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
