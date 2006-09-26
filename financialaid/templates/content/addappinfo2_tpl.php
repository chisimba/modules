<?
$this->objDbStudentInfo =& $this->getObject('dbstudentinfo','studentenquiry');
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

if (!isset($appid)){
    $appid = $this->getParam('appid', NULL);
}

if (is_null($appid)){
    $studentNumber = $this->getParam('stdnum');
    $year = $this->getParam('year');
    $semester = $this->getParam('semester');
    
    $where = "WHERE studentNumber = '".$studentNumber."' AND year = '".$year."' AND semester = '".$semester."'";
    $appinfo = $this->objDBFinancialAidWS->getApplicationWhere($where);
    $studentinfo = $this->objDbStudentInfo->getPersonInfo($studentNumber);
    $appid = $appinfo[0]->id;
}else{
    $appinfo = $this->objDBFinancialAidWS->getApplication($appid);

    if (count($appinfo) > 0){
        $studentinfo = $this->objDbStudentInfo->getPersonInfo($appinfo[0]->studentNumber);
    }
}

if (count($appinfo) > 0){
    $rep = array(
          'FIRSTNAME' => $studentinfo[0]->FSTNAM,
          'LASTNAME' => $studentinfo[0]->SURNAM);
      
    $details = "<h2>".$objLanguage->code2Txt('mod_financialaid_addstudentparticularsfor','financialaid',$rep)."</h2>";

    $content = $objLanguage->languagetext('mod_financialaid_addmoreinfo','financialaid')."<br /><br />";
    $href = new href("index.php?module=financialaid&amp;action=addparttimejob&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=addnextofkin&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid'));
    $content.=$href->show()."<br />";
    $href = new href("index.php?module=financialaid&amp;action=adddependant&amp;appid=$appid",$this->objLanguage->languagetext('mod_financialaid_adddependant','financialaid'));
    $content.=$href->show()."<br /><br /><br />";
    $content .= $objLanguage->languagetext('mod_financialaid_addforanotherstudent','financialaid')."&nbsp;";
    $href = new href("index.php?module=financialaid&amp;action=addappinfo",$this->objLanguage->languagetext('mod_financialaid_here','financialaid'));
    $content.=$href->show()."<br /><br />";

    $content = "<center>".$details." ".$content. "</center>";
}else{
    $details = "<h2>".$objLanguage->languagetext('mod_financialaid_addappinfo','financialaid')."</h2>";
    $content = "<div class='error'>".$this->objLanguage->languagetext('mod_financialaid_applicationnotexisting','financialaid')."</div>";
    $content .= $objLanguage->languagetext('mod_financialaid_addforanotherstudent','financialaid')."&nbsp;";
    $href = new href("index.php?module=financialaid&amp;action=addappinfo",$this->objLanguage->languagetext('mod_financialaid_here','financialaid'));
    $content.=$href->show();
    $content = "<center>".$details." ".$content. "</center>";
}
echo $content;

?>
