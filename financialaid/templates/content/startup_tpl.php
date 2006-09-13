<?php


$content = "<center><h2>".$objLanguage->languagetext('mod_financialaid_welcome','financialaid')."</h2></center>";
$content .= $objLanguage->languagetext('mod_financialaid_intro','financialaid')."<br /><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro2','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_showallapps','financialaid')."</b><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro3','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_searchmarkrange','financialaid')."</b><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro4','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_addstudent','financialaid')."</b><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro5','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_listsponsors','financialaid')."</b><br /><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro6','financialaid')."<br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro7','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_surname','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro8','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_stdnum2','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro9','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_idnumber','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro10','financialaid');


echo $content;
?>
