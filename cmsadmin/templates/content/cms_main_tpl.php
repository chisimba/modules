<?php

//Template to view the different admin functions
$tbl = $this->newObject('htmltable', 'htmlelements');
$tbl->cellpadding = 3;
$tbl->width = "100%";
$tbl->align = "left";

$link = & $this->newObject('link', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$objH = &$this->newObject('htmlheading', 'htmlelements');
$objRound =&$this->newObject('roundcorners','htmlelements');
$objLayer =$this->newObject('layer','htmlelements');

$objIcon->setIcon('control_panel','png', 'icons/cms/');
$objIcon->title = $this->objLanguage->languageText('mod_cmsadmin_cpanel','cmsadmin');
$objH->str = $objIcon->show().'&nbsp;'.$this->objLanguage->languageText('mod_cmsadmin_cpanel','cmsadmin');

$objLayer->str = $objH->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

echo $objRound->show($header.$headShow);//$tbl->show());

echo  $cpanel;

?>
