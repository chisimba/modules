<?
$appid = $this->getParam('appid');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
$yesno = array(
             '0'=>$objLanguage->languagetext('word_no'),
             '1'=>$objLanguage->languagetext('word_yes'));

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_dependantstitle','financialaid',$rep)."</h2>";
$table =& $this->newObject('htmltable','htmlelements');

$dependants = $this->objDBFinancialAidWS->getDependants($appid);

if(count($dependants) > 0){
    $table->startHeaderRow();
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('word_relationship'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_dependantreason','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_category','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_hasincome','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_incometype','financialaid'));
    $table->addHeaderCell($objLanguage->languagetext('mod_financialaid_incomeamount','financialaid'));
    $table->endHeaderRow();


    foreach($dependants as $data)
    {
        $table->startRow();
        $table->addCell($data->firstName);
        $table->addCell($data->relationship);
        $table->addCell($data->dependantReason);
        $table->addCell($data->category);
        $table->addCell($yesno[$data->hasIncome]);
        $table->addCell($data->incomeType);
        $table->addCell($data->incomeAmount);
        $table->endRow();
    }
    $content = $table->show();
}else{
    $content = "<div class='noRecordsMessage'>".$objLanguage->languagetext('mod_financialaid_nodependants','financialaid')."</div>";
}

$content = "<center>".$details." ".$content. "</center>";

echo $content;
?>
