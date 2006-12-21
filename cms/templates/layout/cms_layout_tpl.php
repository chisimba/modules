<?php
//Developer identify yourself and please commebt your code - inserted by Derek 2006 12 16

$objFeatureBox =& $this->newObject('featurebox', 'navigation');
$objBlocks = & $this->newObject('blocks', 'blocks');
$objLucene = & $this->newObject('results', 'lucene');
$objModule = & $this->newObject('modules', 'modulecatalogue');
$objLink = & $this->newObject('link', 'htmlelements');
$objTreeMenu =& $this->newObject('cmstree', 'cmsadmin');
$objUser =& $this->newObject('user', 'security');
$objLanguage =& $this->newObject('language', 'language');
$objArticleBox =& $this->newObject('articlebox', 'cmsadmin');
$objDbBlocks =& $this->newObject('dbblocks', 'cmsadmin');
//Insert script for generating tree menu
//$js = $this->getJavascriptFile('tree.js', 'cmsadmin');
//$this->appendArrayVar('headerParams', $js);
//Include tree menu css script
//$css = '<link rel="stylesheet" type="text/css" media="all" href="modules/cmsadmin/resources/tree.css" />';
//$this->appendArrayVar('headerParams', $css);
//Set to automatically render htmllist into tree menu
//$this->appendArrayVar('bodyOnLoad', 'autoInit_trees()');


/*****************LEFT SIDE ***************************************/
//Navigation
$currentNode = $this->getParam('sectionid', NULL);

$leftSide = $objTreeMenu->getCMSTree($currentNode);
if($objUser->isAdmin()){
    $objAdminLink =& $this->newObject('link', 'htmlelements');
    $objAdminLink->link($this->uri(array(NULL), 'cmsadmin'));
    $objAdminLink->link = $objLanguage->languageText('mod_cms_cmsadmin', 'cms');

    $leftSide .= '<br />';
    $leftSide .= $objAdminLink->show();
}

//Add anything you want to the left template here
//$leftSide .=

/***************** END OF LEFT SIDE *******************************/

if(!$this->getParam('query') == ''){

    $searchResults = $objLucene->show($this->getParam('query'));
} else {
    $searchResults = '';
}

/***************** Right Side Content *******************************/

$hasBlocks = FALSE;
$rightSide = "";

//$isLoggedIn = $objUser->isLoggedIn();

// if(!$isLoggedIn){
    // $hasBlocks = TRUE;
    // $loginBlock = $objDbBlocks->getBlockByName('login');
    // $registerBlock = $objDbBlocks->getBlockByName('register');

    // $rightSide .= $objBlocks->showBlock($loginBlock['blockname'], $loginBlock['moduleid']);
    // $rightSide .= $objBlocks->showBlock($registerBlock['blockname'], $registerBlock['moduleid']);
// } else {
    $currentAction = $this->getParam('action', NULL);
    if($currentAction == 'showsection'){
        $sectionId = $this->getParam('id');
        $pageBlocks = $objDbBlocks->getBlocksForSection($sectionId);    
    } else if($currentAction == 'showcontent' || $currentAction == 'showfulltext' ){
        $pageId = $this->getParam('id');
        $pageBlocks = $objDbBlocks->getBlocksForPage($pageId);    
    } else if($currentAction == 'home' || $currentAction == NULL){
        $pageBlocks = $objDbBlocks->getBlocksForFrontPage();  
    }
    
    if(!empty($pageBlocks)) {
        $hasBlocks = TRUE;
        foreach($pageBlocks as $pbks) {
            $blockId = $pbks['blockid'];
            $blockToShow = $objDbBlocks->getBlock($blockId);

            $rightSide .= $objBlocks->showBlock($blockToShow['blockname'], $blockToShow['moduleid']);
        }
    }
//}

$cssLayout =& $this->newObject('csslayout', 'htmlelements');
if($hasBlocks){
    $cssLayout->setNumColumns(3);
    $cssLayout->setRightColumnContent($rightSide);
} else {
    $cssLayout->setNumColumns(2);
}
$cssLayout->setLeftColumnContent($leftSide);
$cssLayout->setMiddleColumnContent($this->getBreadCrumbs().$this->getContent().$searchResults);

echo $cssLayout->show();

$this->setVar('footerStr', $this->footerStr);
