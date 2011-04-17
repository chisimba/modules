<?php

$sideMenu=$this->getObject('academicsidemenu');
$layout=$this->getObject('cssLayout','htmlelements');
$menuContent=array(
    array(
        'header'=>'head',
        'items'=>array(
            array('menu','link'),
            array('kamenu','kalink')
        )
    )
);

$sideMenu->loadMenu($menuContent);
$layout->setLeftColumnContent($sideMenu->show());
echo $layout->show();
?>
