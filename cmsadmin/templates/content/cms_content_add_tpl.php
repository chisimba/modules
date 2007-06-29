<?php
$objIcon = $this->newObject('geticon', 'htmlelements');
$tbl = $this->newObject('htmltable', 'htmlelements');
$h3 = $this->getObject('htmlheading', 'htmlelements');
//$Icon = $this->newObject('geticon', 'htmlelements');
$objLayer = $this->newObject('layer', 'htmlelements');
//$Icon->setIcon('loading_circles_big');
$objRound =$this->newObject('roundcorners','htmlelements');
$objIcon->setIcon('add_article', 'png', 'icons/cms/');
if(isset($id))
{
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_editcontentitem', 'cmsadmin');	
}
else {
	$h3->str = $objIcon->show().'&nbsp;'. $this->objLanguage->languageText('mod_cmsadmin_contentitem', 'cmsadmin').':'.'&nbsp;'.$this->objLanguage->languageText('word_new');
}

$objLayer->str = $h3->show();
$objLayer->border = '; float:left; align: left; margin:0px; padding:0px;';
$header = $objLayer->show();

$objLayer->str = $topNav;
$objLayer->border = '; float:right; align:right; margin:0px; padding:0px;';
$header .= $objLayer->show();

$objLayer->str = '';
$objLayer->border = '; clear:both; margin:0px; padding:0px;';
$headShow = $objLayer->show();

$display = $objRound->show($header.$headShow);//$tbl->show());
//Show Header
echo $display;
// Show Form
echo $addEditForm;

if ($id != '') {
    echo $this->_objUtils->showContentBlocksForm($id, $section);
} 
?>