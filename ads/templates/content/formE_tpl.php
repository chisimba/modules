<?php 
$forme = $this->getObject("dispforme", "ads");
$forme->setValues($this->formError, $this->formValue, $this->submitAction);
$sectionE = $forme->getForm();
$content= "<div>".$sectionE."</div>";


// Create an instance of the css layout class
$cssLayout = & $this->newObject('csslayout', 'htmlelements');// Set columns to 2
$cssLayout->setNumColumns(2);

$nav = $this->getObject('nav', 'ads');
$toSelect=$this->objLanguage->languageText('mod_ads_section_a_overview', 'ads');
$leftSideColumn = $nav->getLeftContent($toSelect, $this->getParam('action'), $this->getParam('courseid'));
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
?>
