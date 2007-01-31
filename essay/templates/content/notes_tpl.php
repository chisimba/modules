<?php
/*
* Template to display essay notes or markers comment.
* @package essay
*/

/**************** Unset default page variables ***************************/

$this->setVar('pageSuppressIM', TRUE);
$this->setVar('pageSuppressBanner', TRUE);
$this->setVar('pageSuppressToolbar', TRUE);
$this->setVar('suppressFooter', TRUE);

/**************** get notes/comment *****************************/

/**
* @param array $data Array contains the essay name and notes for display.
*/

$topic=$data[0]['topic'];
$notes=$data[0]['notes'];

// set up html elements
$objHead=$this->newObject('htmlheading','htmlelements');

$objLayer = $this->newObject('layer','htmlelements');
$objLayer1 = $this->newObject('layer','htmlelements');
$objLayer2 = $this->newObject('layer','htmlelements');
$objLayer3 = $this->newObject('layer','htmlelements');

/**************** set up display page ********************/

$objHead->type=3;
$objHead->str=$topic;

echo $objHead->show();

$objLayer1->cssClass='even';
$objLayer1->align='center';
$objLayer1->border='1px solid; border-bottom: 0';
$objLayer1->str='<font size=2><b>'.$head.'</b></font>';

$objLayer2->cssClass='odd';
$objLayer2->align='justify';
$objLayer2->border='1px solid; border-top: 0';
$objLayer2->str=$notes;

$this->objIcon->setIcon('close');
$this->objIcon->extra=" onclick='javascript:window.close()'";
$objLayer3->align='center';
$objLayer3->str=$this->objIcon->show();

$objLayer->cssClass='content';
$objLayer->border='1px solid';
$objLayer->align='center';
$objLayer->str= $objLayer1->show().$objLayer2->show().'<p>'.$objLayer3->show();

echo $objLayer->show();

?>