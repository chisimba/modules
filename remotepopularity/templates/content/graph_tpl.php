<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
        
$middleColumn = NULL;
$leftColumn = NULL;

$middleColumn .= $graph;
$leftColumn .= $this->leftMenu->show();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();