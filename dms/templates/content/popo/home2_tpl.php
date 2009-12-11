<?php
$this->loadclass('link','htmlelements');
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';

$iconscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/icons.css').'"/>';
$homejs = '<script language="JavaScript" src="'.$this->getResourceUri('js/home.js').'" type="text/javascript"></script>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);

$this->appendArrayVar('headerParams',$iconscss);
$this->appendArrayVar('headerParams',$homejs);


$upload = "";
if($this->getParam("upload")) {
    $upload = $this->getparam("upload");
}

//url
$dataUrl = str_replace("amp;", "", $this->uri(array('action'=>'getFiles')));

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn =  '<div id ="mainContent">';
$rightSideColumn .= '</div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);

//echo $cssLayout->show();
$mainjs = "<script type='text/javascript'>
        Ext.onReady(function() {

            Ext.QuickTips.init();
               var dataUrl='".$dataUrl."';
               showHome(dataUrl);

                });";
$mainjs .= "</script>";

echo $rightSideColumn;
echo $mainjs;

?>
