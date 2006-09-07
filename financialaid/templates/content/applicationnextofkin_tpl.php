<?
$appid = $this->getParam('appid');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$maritalstatus = array('1'=>'Single',
                  '2'=>'Married',
                  '3'=>'Divorced',
                  '4'=>'Widowed');
$relationship = array('1'=>'Father',
                  '2'=>'Mother',
                  '3'=>'Gaurdian',
                  '4'=>'Spouse');


$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_nextofkintitle','financialaid',$rep)."</h2>";

$nextofkin = $this->objDBFinancialAidWS->getNextofkin($appid);
$content = '';
$table =& $this->newObject('htmltable','htmlelements');
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
        $table->addCell($relationship[$data->relationship]);
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
        $table->addCell($maritalstatus[$data->maritalStatus]);
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
        
        $table->startRow();
        $table->addCell('&nbsp;');
        $table->endRow();
    }
    $content .= $table->show();
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nonextofkin','financialaid')."</div>";
}

$content = "<center>".$details." ".$content. "</center>";

echo $content;
?>
