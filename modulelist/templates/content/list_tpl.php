<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->objLanguage = $this->getObject('language', 'language');
$this->objUser = $this->getObject('user', 'security');
        
$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_modulelist_header', 'modulelist');
$header->type = 1;

$countheader = new htmlHeading();
$countheader->str = count($moduleList)." ".$this->objLanguage->languageText('mod_modulelist_countavailable', 'modulelist');
$countheader->type = 2;

$middleColumn .= $header->show();
$middleColumn .= $countheader->show();

if ($moduleList)
{
    $objFb = $this->newObject('featurebox', 'navigation');
    foreach($moduleList as $moduleRow)
    {
        $header = new htmlHeading();
        $header->str = "Module: ".ucwords($moduleRow['modname']);
        $header->type = 3;
        $middleColumn .= $objFb->show($header->show(), 
          "<div class='" . trim($moduleRow['status'])
          . "'>" . $moduleRow['description'] 
          . "</div><br /><b>Status</b>: " . $moduleRow['status']);
    }
}

if($this->objUser->isLoggedIn()) {
    $leftColumn .= $this->leftMenu->show();
}

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
?>
