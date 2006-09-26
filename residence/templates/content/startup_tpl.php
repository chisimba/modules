<?php
$right =& $this->getObject('blocksearchbox','studentenquiry');
$this->objUser =& $this->getObject('user','security');
$right = $right->show($this->getParam('module'));

$left =& $this->getObject('leftblock');
$left = $left->show($id=null);
$ncnt = 0;

$content = "<center><h2>".$objLanguage->languagetext('mod_residence_welcome','residence')."</h2></center>".'<br />';


$content .= "* ".$objLanguage->languagetext('mod_residence_introh1','residence').' "'.$objLanguage->languagetext('mod_residence_introh1view','residence').'" '.":<br />";
//$content .= "<b>".$objLanguage->languagetext('mod_residence_introh2','residence').":</b><br />";

//------------
$content .= "* ".$objLanguage->languagetext('mod_residence_intro1','residence').' "'.$objLanguage->languagetext('mod_residence_intro1addstudent','residence').'" '."<br />";


//------------------
$content .= "* ".$objLanguage->languagetext('mod_residence_intro2','residence').' "'.$objLanguage->languagetext('mod_residence_introadd','residence').'" '.$objLanguage->languagetext('mod_residence_introadd2','residence')."<br />";

//-----------------------Done

$content .= "* ".$objLanguage->languagetext('mod_residence_intro3','residence')."<br /><br />";
//$content .= "* ".$objLanguage->languagetext('mod_residence_intro1','residence')."</b><br />";
$content .= "<b>".$objLanguage->languagetext('mod_residence_intro4','residence').":</b><br />";
$content .= " ".$objLanguage->languagetext('mod_residence_intro5','residence')." <b>".$objLanguage->languagetext('mod_residence_surname','residence')."</b><br />";
$content .= " ".$objLanguage->languagetext('mod_residence_intro6','residence')." <b>".$objLanguage->languagetext('mod_residence_stdnum','residence')."</b><br />";
$content .= " ".$objLanguage->languagetext('mod_residence_intro7','residence')." <b>".$objLanguage->languagetext('mod_residence_idnum','residence')."</b><br />";
$content .= " ".$objLanguage->languagetext('mod_residence_intro8','residence')." <b>".$objLanguage->languagetext('mod_residence_resultsperpage','residence')."</b><br /><br />";



$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($content);

echo $cssLayout->show();
?>

