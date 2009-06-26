<?php
/*
* Template to display online assignment.
* @package assignment
*/

/**************** Unset default page variables ***************************/

$this->setVar('pageSuppressIM', TRUE);
$this->setVar('pageSuppressBanner', TRUE);
$this->setVar('pageSuppressToolbar', TRUE);
$this->setVar('suppressFooter', TRUE);

/**************** get name/description *****************************/

/**
* @param array $data Array contains the assignment name and description for display.
*/
$name=$mydata[0]['name'];
$description=$mydata[0]['description'];

// set up html elements
$objHead=$this->newObject('htmlheading','htmlelements');

$objLayer = $this->newObject('layer','htmlelements');
$objLayer1 = $this->newObject('layer','htmlelements');
$objLayer2 = $this->newObject('layer','htmlelements');
$objLayer3 = $this->newObject('layer','htmlelements');

/**************** set up display page ********************/

$objHead->type=3;
$objHead->str=$name;

echo "<div align='center'>".$objHead->show().$description."</div>";

$this->objIcon->setIcon('close');
$this->objIcon->extra=" onclick='javascript:window.close()'";
$objLayer3->align='center';
$objLayer3->str=$this->objIcon->show();

$objLayer->cssClass='content';
$objLayer->border='1px solid';
$objLayer->align='center';

$objLayer->str= '<p>'.$objLayer3->show();
echo $objLayer->show();

?>
