<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php

$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$uxcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/ux/css/ux-all.css','htmlelements').'"/>';

$gtxcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/gxt/css/gxt-all.css').'"/>';
$dataviewcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/data-view.css').'"/>';
$iconscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';

$this->appendArrayVar('headerParams', $iconscss);
$this->appendArrayVar('headerParams', $gtxcss);
$this->appendArrayVar('headerParams', $meta);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $uxcss);
$this->appendArrayVar('headerParams', $dataviewcss);
$content= '<h1>WICID</h1><div id="surface"></div><script language="JavaScript" src="'.$this->getResourceUri('js/wicid/wicid.nocache.js').'" type="text/javascript"></script>';
$surface = '<div id="onecolumn">
	    <div id="content">
	    <div id="contentcontent">
	    '.$content.'
	    </div>
	   </div>
	  </div>';
echo $surface;
?>
