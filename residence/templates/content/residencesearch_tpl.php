<?
// Create an instance of the css layout class
$cssLayout2 = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout2->setNumColumns(3);
$left =& $this->getObject('leftblock','residence');
$right =& $this->getObject('resbox','residence');

$cssLayout2->setLeftColumnContent($left->show());
$cssLayout2->setRightColumnContent($right->show());


$cssLayout2->setMiddleColumnContent();


?>
