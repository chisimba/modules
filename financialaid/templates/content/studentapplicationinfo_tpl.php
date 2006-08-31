<?
if (!isset($appnum)){
    $appnum = $this->getParam('appnum');
}
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appnum);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_infotitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]->idNumber;
$stdnum = $stdinfo[0]->studentNumber;
$table =& $this->newObject('htmltable','htmlelements');

if(count($stdinfo) > 0){
    $gender = $stdinfo[0]->gender;
    $saCitizen = $stdinfo[0]->saCitizen;
    $supportingSelf = $stdinfo[0]->supportingSelf;

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
    $table->addCell($stdnum);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
    $table->addCell($gender);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
    if ($saCitizen == 1){
       $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
       $table->addCell($objLanguage->languagetext('word_no'));
    }
	$table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
    if ($supportingSelf == 1){
	   $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
       $table->addCell($objLanguage->languagetext('word_no'));
    }
   	$table->endRow();
}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
