<?
$appnum = $this->getParam('appnum');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appnum);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_nextofkintitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]->idNumber;
$stdnum = $stdinfo[0]->studentNumber;
$table =& $this->newObject('htmltable','htmlelements');

$nextofkin = $this->objDBFinancialAidWS->getNextofkin($appnum);

if(count($nextofkin) > 0){

    foreach($nextofkin as $data)
    {
        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
        $table->addCell($data->idNumber);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
        $table->addCell($data->surname);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
        $table->addCell($data->firstNames);
        $table->endRow();
        
        $table->startRow();
        $table->addCell($objLanguage->languagetext('word_relationship'));
        $table->addCell($data->relationship);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
        $table->addCell($data->strAddress);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
        $table->addCell($data->suburb);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'));
        $table->addCell($data->city);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
        $table->addCell($data->postcode);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
        $table->addCell($data->maritalStatus);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_spouse','financialaid'));
        $table->addCell($data->spouse);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_occupation','financialaid'));
        $table->addCell($data->occupation);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
        $table->addCell($data->employersDetails);
        $table->endRow();

        $table->startRow();
        $table->addCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
        $table->addCell($data->employersTelNo);
        $table->endRow();

    }
}


$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
