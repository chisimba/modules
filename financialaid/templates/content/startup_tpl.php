<?php


$content = "<center><h2>".$objLanguage->languagetext('mod_financialaid_welcome','financialaid')."</h2></center>";
$content .= $objLanguage->languagetext('mod_financialaid_intro','financialaid')."<br /><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro2','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_showallapps','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro3','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_searchmarkrange','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro4','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_addstudent','financialaid')."</b><br />";
$content .= "* ".$objLanguage->languagetext('mod_financialaid_intro5','financialaid')." <b>".$objLanguage->languagetext('mod_financialaid_listsponsors','financialaid')."</b><br /><br />";
$content .= $objLanguage->languagetext('mod_financialaid_intro6','financialaid');


echo $content;
?>
