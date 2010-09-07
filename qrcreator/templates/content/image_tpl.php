<?php
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);

// get the sidebar object
$this->leftMenu = $this->newObject('usermenu', 'toolbar');
$this->loadClass('htmlheading', 'htmlelements');
        
$middleColumn = NULL;
$leftColumn = NULL;

// Add in a heading
$header = new htmlHeading();
$header->str = $this->objLanguage->languageText('mod_qrcreator_codeheader', 'qrcreator');
$header->type = 1;

$middleColumn .= $header->show();
$middleColumn .= '<img src="'.$image.'" />';
// add in some additional text and a link to create another code
$middleColumn .= "<br /><br />";
$this->objShare = $this->getObject('share', 'toolbar');
$this->objShare->setup($this->uri(array('userid' => $this->objUser->userId(), 'id' => $recordid)), $this->objLanguage->languageText("mod_qrcreator_seecode", "qrcreator"), $this->objLanguage->languageText("mod_qrcreator_seecode", "qrcreator")." ");
$middleColumn .= $this->objShare->show();

// Show a download link
$objIcon = $this->getObject('geticon', 'htmlelements');
$url = $this->uri(array('action' => 'downloadcode', 'file' => $image, 'userid' => $this->objUser->userId()));
$dlicon = $objIcon->getDownloadIcon($url);
// $middleColumn .= $dlicon;

$headerold = new htmlHeading();
$headerold->str = $this->objLanguage->languageText('mod_qrcreator_prevcodes', 'qrcreator');
$headerold->type = 1;

$middleColumn .= $headerold->show();

$leftColumn .= $this->leftMenu->show();

$cssLayout->setMiddleColumnContent($middleColumn);
$cssLayout->setLeftColumnContent($leftColumn);
echo $cssLayout->show();
