<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$utils=$this->getObject('userutils');

$meta = '<meta name=\'gwt:module\' content=\'org.dms.Test1=org.dms.Test1\'>';
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
echo '<script language="JavaScript" src="'.$this->getResourceUri('js/org.dms.Startup/org.dms.Startup.nocache.js').'" type="text/javascript"></script>';

?>
