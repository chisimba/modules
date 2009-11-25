<?php
$cssLayout = &$this->newObject('csslayout', 'htmlelements');
$objMenu = &$this->newObject('sidemenu', 'toolbar');
$objFeatureBox = $this->newObject ( 'featurebox', 'navigation' );
//Menu Links
 $pageLink = "<ul>";

	$homelink = new link($this->uri(array(
			  'module' => 'eportfolio'
	)));
	$homelink->link = $this->objLanguage->languageText("mod_eportfolio_eportfoliohome","eportfolio","ePortfolio Home");
	$homeManage = $homelink->show();
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$homeManage."</li>";

	$configlink = new link($this->uri(array(
			  'module' => 'eportfolio',
			  'action' => 'configureviews'
	)));
	$configlink->link = $this->objLanguage->languageText("mod_eportfolio_configure","eportfolio","Configure")." ".$this->objLanguage->languageText("mod_eportfolio_wordGroup","eportfolio","Group")." ".$this->objLanguage->languageText("mod_eportfolio_views","eportfolio","Views");
	$configManage = $configlink->show();
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$configManage."</li>";
 
	$mngviewlink = new link($this->uri(array(
			  'module' => 'eportfolio',
			  'action' => 'myview'
	)));
	$mngviewlink->link = $this->objLanguage->languageText("mod_eportfolio_view","eportfolio","View")." ".$this->objLanguage->languageText("mod_eportfolio_own","eportfolio","Own")." ".$this->objLanguage->languageText("mod_eportfolio_wordEportfolio","eportfolio","ePortfolio");
	$linkviewManage = $mngviewlink->show();

 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$linkviewManage."</li>";
	$otviewlink = new link($this->uri(array(
			  'module' => 'eportfolio',
			  'action' => 'viewothersportfolio'
	)));
	$otviewlink->link = $this->objLanguage->languageText("mod_eportfolio_view","eportfolio","View")." ".$this->objLanguage->languageText("phrase_othersePortfolio","eportfolio","Others Portfolio");
	$linkviewothers = $otviewlink->show();
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$linkviewothers."</li>";

	$mngpdflink = new link($this->uri(array(
		   'module' => 'eportfolio',
		   'action' => 'makepdf'
		)));
	$mngpdflink->link = $this->objLanguage->languageText("mod_eportfolio_saveaspdf","eportfolio","Save ePortfolio as PDF");
	$linkpdfManage = $mngpdflink->show();

 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$linkpdfManage."</li>";

	$mngExportlink = new link($this->uri(array(
		   'module' => 'eportfolio',
		   'action' => 'export'
	)));
	$mngExportlink->link = $this->objLanguage->languageText("mod_eportfolio_export","eportfolio","Export");
	$linkExportManage = $mngExportlink->show();

 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$linkExportManage."</li>";

	$mngImportlink = new link($this->uri(array(
			  'module' => 'eportfolio',
			  'action' => 'import'
	)));
	$mngImportlink->link = $this->objLanguage->languageText("mod_eportfolio_import","eportfolio","Import");
	$linkImportManage = $mngImportlink->show();
 
 $pageLink .= "<li>&nbsp;&nbsp;&nbsp;".$linkImportManage."</li>";
 $pageLink .= "</ul>";
 //Add menu to fieldset
 $fieldset = $this->newObject('fieldset', 'htmlelements');
 $fieldset->contents = $pageLink;

 //add fieldset to featurebox
 $infeat = $objFeatureBox->show ("ePortfolio", $fieldset->show()."<br />","epbox", $blockType = NULL, $titleLength = 20, $wrapStr = TRUE, $showToggle = TRUE, $hidden = 'default', $showTitle = TRUE, $cssClass = 'featurebox', $cssId = '');
$cssLayout->setLeftColumnContent($infeat);
$cssLayout->setMiddleColumnContent($this->getContent());
echo $cssLayout->show();
?>
