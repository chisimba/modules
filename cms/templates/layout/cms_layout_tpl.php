<?php


$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objBlocks = & $this->newObject('blocks', 'blocks');
$objLucene = & $this->newObject('results', 'lucene');
$objModule = & $this->newObject('modules', 'modulecatalogue');
$objLink = & $this->newObject('link', 'htmlelements');
$objTreeMenu =& $this->newObject('cmstree', 'cmsadmin');
$objUser =& $this->newObject('user', 'security');
$objLanguage =& $this->newObject('language', 'language');
$objArticleBox = $this->newObject('articlebox', 'cmsadmin');

//Insert script for generating tree menu
$js = $this->getJavascriptFile('tree.js', 'cmsadmin');
$this->appendArrayVar('headerParams', $js);
//Include tree menu css script
$css = '<link rel="stylesheet" type="text/css" media="all" href="modules/cmsadmin/resources/tree.css" />';
$this->appendArrayVar('headerParams', $css);
//Set to automatically render htmllist into tree menu
$this->appendArrayVar('bodyOnLoad', 'autoInit_trees()');

/******************* BEGIN RIGHTSIDE BLOCKS ******************************/
// Right Column initialize
$rightSideColumn = "";
//Add the getting help block
$objLink->href = $this->uri(null, 'calendar');
$objLink->link = $objBlocks->showBlock('calendar', 'calendar');
$rightSideColumn .= $objBlocks->showBlock('gettinghelp', 'help');
//simple calendar
$rightSideColumn .= '<br />'. $objLink->show();
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




/*****************LEFT SIDE ***************************************/
//Navigation
$currentNode = $this->getParam('sectionid', NULL);

$leftSide = $objTreeMenu->buildTree($currentNode);
if($objUser->isAdmin()){
  $objAdminLink =& $this->newObject('link', 'htmlelements');
  $objAdminLink->link($this->uri(array(NULL), 'cmsadmin'));
  $objAdminLink->link = $objLanguage->languageText('mod_cms_cmsadmin', 'cms');
  
  $leftSide .= '<br/>';
  $leftSide .= $objAdminLink->show();
}


/***************** END OF LEFT SIDE *******************************/

if(!$this->getParam('query') == '')
{
	
	$searchResults = $objLucene->show($this->getParam('query'));
} else {
    $searchResults = '';
}

       $cssLayout =& $this->newObject('csslayout', 'htmlelements');
       $cssLayout->setNumColumns(3);
       $cssLayout->setLeftColumnContent($leftSide);


	   
       $cssLayout->setMiddleColumnContent($this->getBreadCrumbs().$objArticleBox->show( $this->getContent()).$searchResults.$this->footerStr);
       $cssLayout->setRightColumnContent($rightSideColumn);
       echo $cssLayout->show(); 

