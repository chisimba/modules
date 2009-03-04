<?php
header("Content-Type: text/html;charset=utf-8");
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$objImView = $this->getObject('jbviewer');
$this->objFeatureBox = $this->getObject('featurebox', 'navigation');
$objWashout = $this->getObject('washout', 'utilities');
//$this->objImOps = $this->getObject('imops', 'im');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_jabberblog_jabberblogof', 'jabberblog')." ".$this->objUser->fullName($this->jposteruid);
$header->type = 1;

$script = '<script type="text/JavaScript" src="resources/rounded_corners.inc.js"></script>
    <script type="text/JavaScript">
      window.onload = function() {
          settings = {
              tl: { radius: 10 },
              tr: { radius: 10 },
              bl: { radius: 10 },
              br: { radius: 10 },
              antiAlias: true,
              autoPad: true
          }
          var myBoxObject = new curvyCorners(settings, "rounded");
          myBoxObject.applyCornersToAll();
      }
    </script>';
        $this->appendArrayVar('headerParams', $script);


$objPagination = $this->newObject('pagination', 'navigation');
$objPagination->module = 'jabberblog';
$objPagination->action = 'viewallajax';
$objPagination->id = 'jabberblog';
$objPagination->numPageLinks = $pages;
$objPagination->currentPage = $pages - 1;


$middleColumn .= $header->show().'<br/>'.$objPagination->show();
//$middleColumn .= $objImView->renderOutputForBrowser($msgs);

$rssLink = $this->getObject('link', 'htmlelements');
$rssLink->href = $this->uri(array( 'action' => 'rss'));
$rssLink->link = $this->objLanguage->languageText("mod_jabberblog_showrss", "jabberblog");
$objLT = $this->getObject('block_lasttweet', 'twitter');

if (!$this->objUser->isLoggedIn()) {
    $leftColumn .= $objImView->showUserMenu();
    $leftColumn .= $objImView->getStatsBox();
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText("mod_jabberblog_feed", "jabberblog"), $rssLink->show() );
    // show the last tweet block from the 'ol twitter stream
    $leftColumn .= $objLT->show();
} else {
    $leftColumn .= $this->leftMenu->show();
    $leftColumn .= $objImView->getStatsBox();
    $leftColumn .= $this->objFeatureBox->show ( $this->objLanguage->languageText("mod_jabberblog_feed", "jabberblog"), $rssLink->show() );
    $leftColumn .= $objLT->show();
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
