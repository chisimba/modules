<?php
$extbase = '<script language="javascript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="javascript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';


$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);

$objHead=$this->newObject('htmlheading','htmlelements');
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent( $postLoginMenu->show());
// Add Left column
//$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Left column
$cssLayout->setLeftColumnContent($leftSideColumn);

// Add Right Column
$cssLayout->setMiddleColumnContent($this->formError->getFormattedError("general"));

//Output the content to the page
echo $cssLayout->show();
?>
