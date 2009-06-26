<?php
$cssLayout = $this->newObject ( 'csslayout', 'htmlelements' );
$cssLayout->setNumColumns ( 2 );

// get the sidebar object
$this->loadClass ( 'htmlheading', 'htmlelements' );
$this->objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );
$this->objViewer = $this->getObject('viewer');

$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading ( );
$header->str = $this->objLanguage->languageText ( 'mod_brandmonday_bmtweets', 'brandmonday' );
$header->type = 1;

// Add in a tagline
$headertag = new htmlHeading ( );
$headertag->str = $this->objLanguage->languageText ( 'mod_brandmonday_bminit', 'brandmonday' );
$headertag->type = 3;

$middleColumn .= $header->show().$headertag->show();
$middleColumn .= $this->objViewer->renderCompView($resPlus, $resMinus, $resFail);

$leftColumn = $this->objViewer->renderLeftBlocks();

$cssLayout->setMiddleColumnContent ( $middleColumn );
$cssLayout->setLeftColumnContent ( $leftColumn );
echo $cssLayout->show ();
?>