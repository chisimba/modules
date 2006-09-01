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
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_infotitle','financialaid',$rep)."</h2>";

$table =& $this->newObject('htmltable','htmlelements');

if(count($stdinfo) > 0){
    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
    $table->addCell($stdinfo[0]->studentNumber);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
    $table->addCell($stdinfo[0]->idNumber);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
    $table->addCell($stdinfo[0]->gender);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
    if ($stdinfo[0]->saCitizen == 1){
       $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
        if (!is_null($stdinfo[0]->saCitizen)){
            $table->addCell($objLanguage->languagetext('word_no'));
        }
    }
	$table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
    $table->addCell($stdinfo[0]->maritalStatus);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_courseofstudy','financialaid'));
    $table->addCell($stdinfo[0]->course);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_fulltime','financialaid'));
    if ($stdinfo[0]->fulltime == 1){
	   $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
        if (!is_null($stdinfo[0]->fulltime)){
            $table->addCell($objLanguage->languagetext('word_no'));
        }
    }
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_majorsubjects','financialaid'));
    $table->addCell($stdinfo[0]->majors);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_hometelno','financialaid'));
    $table->addCell($stdinfo[0]->homeTelNo);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_cellno','financialaid'));
    $table->addCell($stdinfo[0]->cellNo);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
    if ($stdinfo[0]->supportingSelf == 1){
	   $table->addCell($objLanguage->languagetext('word_yes'));
    }else{
        if (!is_null($stdinfo[0]->supportingSelf)){
            $table->addCell($objLanguage->languagetext('word_no'));
        }
    }
   	$table->endRow();

}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
