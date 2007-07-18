<?php
$script ='
<script type="text/javascript">
//<![CDATA[
YAHOO.example.onMenuReady = function() {

                // Instantiate and render the menu

                var oMenu = new YAHOO.widget.Menu(
                                    "productsandservices", 
                                    {
                                        position:"static", 
                                        hidedelay:750, 
                                        lazyload:true 
                                    }
                                );

                oMenu.render();

            };


            // Initialize and render the menu when it is available in the DOM

            YAHOO.util.Event.onContentReady("productsandservices", YAHOO.example.onMenuReady);





//]]>
</script>
';


$objFeatureBox = $this->newObject('featurebox', 'navigation');
$objBlocks =  $this->newObject('blocks', 'blocks');
$objLucene =  $this->newObject('results', 'lucene');
$objModule =  $this->newObject('modules', 'modulecatalogue');
$objLink =  $this->newObject('link', 'htmlelements');
$objTreeMenu = $this->newObject('cmstree', 'cmsadmin');
$objUser = $this->newObject('user', 'security');
$objLanguage = $this->newObject('language', 'language');
$objArticleBox = $this->newObject('articlebox', 'cmsadmin');
$objDbBlocks = $this->newObject('dbblocks', 'cmsadmin');
//Insert script for generating tree menu
$this->appendArrayVar('headerParams', $this->getJavascriptFile('yahoo/yahoo.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('event/event.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('dom/dom.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('container/container.js', 'yahoolib'));
$this->appendArrayVar('headerParams', $this->getJavascriptFile('menu/menu.js', 'yahoolib'));
$this->appendArrayVar('headerParams',$script);

$css = '<link rel="stylesheet" type="text/css" media="all" href="'.$this->getResourceURI("menu/assets/menu.css", 'yahoolib').'" />';
$this->appendArrayVar('headerParams', $css);

/*****************LEFT SIDE ***************************************/

// Navigation
$currentNode = $this->getParam('sectionid', NULL);

if(!isset($rss)){
    $rss = '';
}
$leftSide = $this->objLayout->getLeftMenu($currentNode, $rss);

// Add blocks
$currentAction = $this->getParam('action', NULL);

switch($currentAction){
    case 'showsection':
        $sectionId = $this->getParam('id');
        $pageBlocks = $objDbBlocks->getBlocksForSection($sectionId);
        $leftPageBlocks = $objDbBlocks->getBlocksForSection($sectionId, 1);
        break;
    
    case 'showcontent':
    case 'showfulltext':
        $sectionId = $this->getParam('sectionid');
        $pageId = $this->getParam('id');
        $leftPageBlocks = $objDbBlocks->getBlocksForPage($pageId, $sectionId, 1);
        $pageBlocks = $objDbBlocks->getBlocksForPage($pageId, $sectionId);
        break;
    
    case 'home':
    case '':
        $leftPageBlocks = $objDbBlocks->getBlocksForFrontPage(1);
        $pageBlocks = $objDbBlocks->getBlocksForFrontPage();
        break;
}

// Add left blocks    
if(!empty($leftPageBlocks)) {
    foreach($leftPageBlocks as $pbks) {
        $blockId = $pbks['blockid'];
        $blockToShow = $objDbBlocks->getBlock($blockId);

        $leftSide .= $objBlocks->showBlock($blockToShow['blockname'], $blockToShow['moduleid']);
    }
}

/***************** END OF LEFT SIDE *******************************/

if(!$this->getParam('query') == ''){

    $searchResults = $objLucene->show($this->getParam('query'));
} else {
    $searchResults = '';
}

/***************** Right Side Content *******************************/

$hasBlocks = FALSE;
$rightSide = '';

// Add right blocks    
if(!empty($pageBlocks)) {
    $hasBlocks = TRUE;
    foreach($pageBlocks as $pbks) {
        $blockId = $pbks['blockid'];
        $blockToShow = $objDbBlocks->getBlock($blockId);

        $rightSide .= $objBlocks->showBlock($blockToShow['blockname'], $blockToShow['moduleid']);
    }
}


$cssLayout = $this->newObject('csslayout', 'htmlelements');
if($hasBlocks){
    $cssLayout->setNumColumns(3);
    $cssLayout->setRightColumnContent($rightSide);
} else {
    $cssLayout->setNumColumns(2);
}
$cssLayout->setLeftColumnContent($leftSide.'<br />');
$cssLayout->setMiddleColumnContent($this->getBreadCrumbs().$this->getContent().'<br />'.$searchResults);

echo $cssLayout->show();

$this->setVar('footerStr', $this->footerStr);
