<?php

$extbase = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/adapter/ext/ext-base.js','htmlelements').'" type="text/javascript"></script>';
$extalljs = '<script language="JavaScript" src="'.$this->getResourceUri('ext-3.0-rc2/ext-all.js','htmlelements').'" type="text/javascript"></script>';
$formEjs = '<script language="JavaScript" src="'.$this->getResourceUri('js/formejs.js','ads').'" type="text/javascript"></script>';
$extallcss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('ext-3.0-rc2/resources/css/ext-all.css','htmlelements').'"/>';
$buttonscss = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('css/buttons.css','ads').'"/>';

$this->appendArrayVar('headerParams', $extbase);
$this->appendArrayVar('headerParams', $extalljs);
$this->appendArrayVar('headerParams', $extallcss);
$this->appendArrayVar('headerParams', $buttonscss);
$this->appendArrayVar('headerParams', $formEjs);

$courseid=$this->getParam('courseid');
$myscript = " Ext.onReady(function(){
loadFormEJS('".$courseid."');

})";

$forme = $this->getObject("dispforme", "ads");
$forme->setValues($this->formError, $this->formValue, $this->submitAction);
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->getParam('courseid'));
$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$readonlyWarn=$editable == 'false' ?"<font color=\"red\"><h1>This is a read-only document</h1></font>":"";

$sectionE =$readonlyWarn. $forme->getForm();
$content= "<div>".$sectionE."</div>";


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$currentEditor=$this->objDocumentStore->getCurrentEditor($this->getParam('courseid'));
$editable=$currentEditor == $this->objUser->email() ? 'true':'false';
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'),$editable);
$cssLayout->setLeftColumnContent($leftSideColumn);
//$rightSideColumn='<h1>'.$coursedata['title'].'</h1>';
$rightSideColumn='<div style="padding:10px;">';

//Add the table to the centered layer
$rightSideColumn .= $content;
$rightSideColumn.= '</div>';
// Add Right Column
$cssLayout->setMiddleColumnContent($rightSideColumn);

//Output the content to the page
echo $cssLayout->show();
echo $this->formValue->getSaveScript($this->submitAction);
echo "<script type=\"text/javascript\">".$myscript."</script>";

?>
