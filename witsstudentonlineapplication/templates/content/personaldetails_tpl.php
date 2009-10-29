<?php

//we load the extjs libs
$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';

//load our js
$personaldetailsjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/personaldetails.js').'" type="text/javascript"></script>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css').'"/>';


$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $buttonscss);
$this->appendArrayVar('headerParams', $personaldetailsjs);

$surface='
<div id="surface"></div>

';


$mainjs="
Ext.onReady(function(){
initPersonalDetailsForm();
});
";
$nav=$this->getObject('nav');
$leftContent=$nav->getLeftContent($toSelect, $action, $courseid, $editable);
$middleContent=$surface;
// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($leftContent);
$cssLayout->setMiddleColumnContent($middleContent);
echo $cssLayout->show();

echo "<script type='text/javascript'>".$mainjs."</script>";


?>
