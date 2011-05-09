<?php

//load css layout class
$this->loadClass('csslayout','htmlelements');

$lef_nav = '<ul><li><a href="?module=tzschoolfees">Home</a></li>';
$lef_nav .= '<li><a href="?module=tzschoolfees&action=add_details">Add student details</li>';
$lef_nav .= "<li>View</li>";
$lef_nav .= "<li>Generate receipt</li></ul>";

//$cssLayout = $this->newObject('csslayout','htmlelements');
$cssLayout = $this->newObject('csslayout','htmlelements');
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($lef_nav);
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>
