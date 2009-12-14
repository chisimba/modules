<?php
$this->loadclass('link','htmlelements');
$this->loadclass('link','htmlelements');
$objIcon= $this->newObject('geticon','htmlelements');

/*$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';*/

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/adapter/ext/ext-base.js').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('js/ext-3.0.0/ext-all.js').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('js/ext-3.0.0/resources/css/ext-all.css').'"/>';

$uxjs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ux/FileUploadField.js','htmlelements').'" type="text/javascript"></script>';
$iconscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';
$homejs = '<script language="JavaScript" src="'.$this->getResourceUri('js/home.js').'" type="text/javascript"></script>';
$fucss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/file-upload.css').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $uxjs);
$this->appendArrayVar('headerParams',$iconscss);
$this->appendArrayVar('headerParams',$fucss);
$this->appendArrayVar('headerParams',$homejs);

$upload = "";
if($this->getParam("upload")) {
    $upload = $this->getparam("upload");
}


$dataUrl = str_replace("amp;", "", $this->uri(array('action'=>'getFiles')));
$createFolderUrl = str_replace("amp;", "", $this->uri(array('action'=>'createfolder')));
$renameFolderUrl = str_replace("amp;", "", $this->uri(array('action'=>'renamefolder')));
$deleteFolderUrl = str_replace("amp;", "", $this->uri(array('action'=>'deletefolder')));
$uploadUrl = str_replace("amp;", "", $this->uri(array('action'=>'doupload')));

// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$postLoginMenu  = $this->newObject('postloginmenu','toolbar');
$cssLayout->setLeftColumnContent($postLoginMenu->show());
$rightSideColumn =  '<div id="upload-win" class="x-hidden"><div class="x-window-header"></div></div><div id="toolbar"></div>';
$rightSideColumn .= '<div id ="mainContent"></div>';
$cssLayout->setMiddleColumnContent($rightSideColumn);

//echo $cssLayout->show();
$mainjs = "<script type='text/javascript'>
        Ext.onReady(function() {

            Ext.QuickTips.init();

            var dataUrl='".$dataUrl."',
                createFolderUrl='".$createFolderUrl."',
                renameFolderUrl='".$renameFolderUrl."';
                deleteFolderUrl='".$deleteFolderUrl."';
                uploadUrl = '".$uploadUrl."';
            showHome(dataUrl,createFolderUrl, renameFolderUrl, deleteFolderUrl, uploadUrl);
        });";
$mainjs .= "</script>";

echo $cssLayout->show();
//echo $rightSideColumn;
echo $mainjs;

?>
