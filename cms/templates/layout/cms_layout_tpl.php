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
       $cssLayout->setNumColumns(2);
       $cssLayout->setLeftColumnContent($leftSide);


	   
       $cssLayout->setMiddleColumnContent($this->getBreadCrumbs().$this->getContent().$searchResults.$this->footerStr);
       echo $cssLayout->show();

