<?
/*
* Template to display the lecturers comment on an assignment.
* @package assignment
*/

/**
* @param array $data Array contains the assignment name and comments.
*/

// Suppress Default Page Settings
$this->setVar('pageSuppressContainer',TRUE);
$this->setVar('pageSuppressIM',TRUE);
$this->setVar('pageSuppressBanner',TRUE);
$this->setVar('pageSuppressToolbar',TRUE);
$this->setVar('suppressFooter',TRUE);

$name = $data[0]['name'];
$comment = $data[0]['comment'];

// set up html elements
$objHead = $this->newObject('htmlheading','htmlelements');
$objLayer = $this->newObject('layer','htmlelements');
$objLayer1 = $this->newObject('layer','htmlelements');
$objLayer2 = $this->newObject('layer','htmlelements');
$objLayer3 = $this->newObject('layer','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');

// set up language items
$head = $this->objLanguage->languageText('mod_assignment_comment');

/**************** set up display page ********************/

$objHead->type=3;
$objHead->str=$name;

echo $objHead->show();

$objLayer1->cssClass='even';
$objLayer1->align='center';
$objLayer1->border='1px solid; border-bottom: 0';
$objLayer1->str='<font size=2><b>'.$head.'</b></font>';

$objLayer2->cssClass='odd';
$objLayer2->align='justify';
$objLayer2->border='1px solid; border-top: 0';
$objLayer2->str=$comment;

$objIcon->setIcon('close');
$objIcon->extra=" onclick='javascript:window.close()'";
$objLayer3->align='center';
$objLayer3->str=$objIcon->show();

$objLayer->cssClass='content';
$objLayer->border='1px solid';
$objLayer->align='center';
$objLayer->str=$objLayer1->show().$objLayer2->show().'<p>'.$objLayer3->show();

echo $objLayer->show();

?>