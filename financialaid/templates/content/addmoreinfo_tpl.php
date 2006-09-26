<?
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

if (!isset($appid)){
    $appid = $this->getParam('appid');
}

$appinfo = $this->objDBFinancialAidWS->getApplication($appid);

if (count($appinfo) > 0){
    $studentinfo = $this->objDbStudentInfo->getPersonInfo($appinfo[0]->studentNumber);
}

if (count($studentinfo) > 0){
    $rep = array(
          'FIRSTNAME' => $studentinfo[0]->FSTNAM,
          'LASTNAME' => $studentinfo[0]->SURNAM);
      
    $details = "<h2>".$objLanguage->code2Txt('mod_financialaid_recordadded','financialaid',$rep)."</h2>";

    $content = $objLanguage->languagetext('mod_financialaid_addmoreinfo','financialaid')."<br /><br />";
    $href = new href("index.php?module=financialaid&amp;action=addparttimejob&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=addnextofkin&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=adddependant&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_adddependant','financialaid'));
    $content.=$href->show()."<br /><br /><br />";
    $content .= $objLanguage->languagetext('mod_financialaid_addmoreinfoalternative','financialaid')."&nbsp;";
    $href = new href("index.php?module=financialaid&amp;action=applicationinfo&amp;appid=".$appid,$this->objLanguage->languagetext('mod_financialaid_here','financialaid'));
    $content.=$href->show()."<br /><br />";

    $content = "<center>".$details." ".$content. "</center>";
}
echo $content;

?>
