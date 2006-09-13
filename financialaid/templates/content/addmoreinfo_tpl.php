<?
if (!isset($appid)){
    $appid = $this->getParam('appid');
}
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);

$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_recordadded','financialaid',$rep)."</h2>";


if(count($stdinfo) > 0){
    $content = $objLanguage->languagetext('mod_financialaid_addmoreinfo','financialaid')."<br /><br />";
    $href = new href("index.php?module=financialaid&amp;action=addparttimejob&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=addnextofkin&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=adddependant&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_adddependant','financialaid'));
    $content.=$href->show()."<br /><br /><br />";
    $content .= $objLanguage->languagetext('mod_financialaid_addmoreinfoalternative','financialaid')."&nbsp;";
    $href = new href("index.php?module=financialaid&amp;action=applicationinfo",$this->objLanguage->languagetext('mod_financialaid_here','financialaid'));
    $content.=$href->show()."<br /><br />";
}
$content = "<center>".$details." ".$content. "</center>";

echo $content;

?>
