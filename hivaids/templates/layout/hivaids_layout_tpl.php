<?
/**
* @package hivaids
*
* Layout template for the hivaids module
*/

$objBlocks = $this->getObject('blocks', 'blocks');
$login = $objBlocks->showBlock('login', 'security');

$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

$cssLayout->setLeftColumnContent($login.'<br />');
$cssLayout->setMiddleColumnContent($this->getContent());

echo $cssLayout->show();
?>