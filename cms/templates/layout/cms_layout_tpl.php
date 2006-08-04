<?php


$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objBlocks = & $this->newObject('blocks', 'blocks');
$objLucene = & $this->newObject('results', 'lucene');

/******************* BEGIN RIGHTSIDE BLOCKS ******************************/
// Right Column initialize
$rightSideColumn = "";
//Add the getting help block
$rightSideColumn .= $objBlocks->showBlock('gettinghelp', 'help');
//Add the latest in blog as a a block
$rightSideColumn .= $objBlocks->showBlock('latest', 'blog');
//Add the latest in blog as a a block
$rightSideColumn .= $objBlocks->showBlock('latestpodcast', 'podcast');
//Add a block for chat
$rightSideColumn .= $objBlocks->showBlock('chat', 'chat');
//Add a block for the google api search
$rightSideColumn .= $objBlocks->showBlock('google', 'websearch');
//Put the google scholar google search
$rightSideColumn .= $objBlocks->showBlock('scholarg', 'websearch');
//Put a wikipedia search
$rightSideColumn .= $objBlocks->showBlock('wikipedia', 'websearch');
//Put a dictionary lookup
$rightSideColumn .= $objBlocks->showBlock('dictionary', 'dictionary');
/******************* END  RIGHTSIDE BLOCKS ******************************/

if(!$this->getParam('query') == '')
{
	
	$searchResults = $objLucene->show($this->getParam('query'));
}

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
       $cssLayout->setNumColumns(3);
       $cssLayout->setLeftColumnContent($this->getSectionMenu());
       $cssLayout->setMiddleColumnContent($this->getBreadCrumbs().$this->getContent().$searchResults.$this->footerStr);
       $cssLayout->setRightColumnContent($rightSideColumn);
       echo $cssLayout->show(); 

?>