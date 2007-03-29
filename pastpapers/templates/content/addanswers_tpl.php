<?php 
//template for ading answers to pastpapers
$content = "";
$form =& $this->getObject('form','htmlelements');
//get the id of the paper to be added
$paperid = $this->getParam('paperid',NULL);

//get the paper details
$this->pastapapers =& $this->getObject('pastpaper');
$paperdetails = $this->pastapapers->getPaperDetails($paperid);

$heading = $this->getObject('htmlheading','htmlelements');
$heading->align ="center";
$heading->str = $this->objLanguage->languageText('mod_pastpapers_addanswersto','pastpapers')."&nbsp;".$paperdetails;


$content .= $heading->show();

echo $content;
?>