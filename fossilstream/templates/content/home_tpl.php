<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php


$objWashout = $this->getObject('washout', 'utilities');

$vid='[FLV: url= http://localhost/chisimba/somefile.flv, width=800,
	 height=600]';
$objH1 = $this->newObject('htmlheading', 'htmlelements');
$objH2 = $this->newObject('htmlheading', 'htmlelements');
$cssLayout = $this->getObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$leftContent = $this->objNewsMenu->generateMenu();
$leftContent .= '<div id="newsfeeds">'.$this->objNewsStories->getFeedLinks().'</div>';
$objH1->type=1;
$objH1->str="The Fossil";
$objH2->type=2;
$objH2->str="Coming soon...";
$cssLayout->setLeftColumnContent($leftContent);
$content='[IFRAME]http://146.141.9.151/flv/player2/flash.html|400|324[/IFRAME]';
$content=$objWashout->parseText($content);
$cssLayout->setMiddleColumnContent($objH1->show(). $objH2->show().$content);

echo $cssLayout->show();
?>
