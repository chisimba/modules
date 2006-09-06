<?
$appid = $this->getParam('appid');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_studentfamilytitle','financialaid',$rep)."</h2>";
$table =& $this->newObject('htmltable','htmlelements');

$parttimejobs = $this->objDBFinancialAidWS->getStudentsInFamily($appid);

if(count($parttimejobs) > 0){
    $table->startHeaderRow();
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_institution','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('word_course'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
    $table->endHeaderRow();

    foreach($parttimejobs as $data)
    {
        $table->startRow();
        $table->addCell($data->firstName);
        $table->addCell($data->institution);
        $table->addCell($data->course);
        $table->addCell($data->yearOfStudy);
        $table->addCell($data->studentNumber);
        $table->endRow();
    }
    $content = $table->show();
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nodependants','financialaid')."</div>";
}
$content = "<center>".$details." ".$content. "</center>";

echo $content;
?>
