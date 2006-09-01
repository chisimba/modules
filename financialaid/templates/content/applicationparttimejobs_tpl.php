<?
$appid = $this->getParam('appid');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_parttimejobtitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]->idNumber;
$stdnum = $stdinfo[0]->studentNumber;
$table =& $this->newObject('htmltable','htmlelements');

$parttimejobs = $this->objDBFinancialAidWS->getParttimejob($appid);

if(count($parttimejobs) > 0){
    $table->startHeaderRow();
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_jobtitle','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
    $table->endHeaderRow();


    foreach($parttimejobs as $data)
    {
        $table->startRow();
        $table->addCell($data->jobTitle);
        $table->addCell($data->employersDetails);
        $table->addCell($data->employersTelNo);
        $table->endRow();
    }
}

$content = "<center>".$details." ".$table->show(). "</center>";

// Create an instance of the css layout class

echo $content;

?>
