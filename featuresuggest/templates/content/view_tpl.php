<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('href', 'htmlelements');
        
$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_featuresuggest_welcome', 'featuresuggest');
$header->type = 1;

$middleColumn .= '<div id="heading" class="rounded">'.$header->show().'</div>';
$middleColumn .= $str;
$middleColumn .= $this->objUI->formatUI($str);
if($this->objUser->isLoggedIn()) {
    $middleColumn .= $this->objUI->addForm();
}
//$leftColumn .= $this->objOps->userSearchBox();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
