<?
$appnum = $this->getParam('appnum');

$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appnum);
$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_dependantstitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]->idNumber;
$stdnum = $stdinfo[0]->studentNumber;
$table =& $this->newObject('htmltable','htmlelements');

$dependants = $this->objDBFinancialAidWS->getDependants($appnum);

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
        if($data->hasIncome == 0){
            $table->addCell('No');
        }else{
            $table->addCell('Yes');
        }
        $table->addCell($data->incomeType);
        $table->addCell($data->incomeAmount);
        $table->endRow();
    }
}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
