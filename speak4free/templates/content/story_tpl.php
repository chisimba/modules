<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
<?php
$this->loadClass('link', 'htmlelements');

$content= $this->objViewerUtils->getTopic($category);
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav=$this->getObject('nav');
$leftColumnContent=$nav->show();;
$cssLayout->setLeftColumnContent($leftColumnContent);
$rightSideColumn =  $content;
$cssLayout->setMiddleColumnContent($rightSideColumn);
echo $cssLayout->show();
?>
