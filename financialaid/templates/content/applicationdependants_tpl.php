<?
$appnum = $this->getParam('appnum');

$this->objDBApplication =& $this->getObject('dbapplication');

$stdinfo = $this->objDBApplication->getApplication($appnum);
$stname = $stdinfo[0]['firstnames'];
$stsname = $stdinfo[0]['surname'];

$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_dependantstitle','financialaid',$rep)."</h2>";
$idnumber = $stdinfo[0]['idnumber'];
$stdnum = $stdinfo[0]['studentnumber'];
$table =& $this->newObject('htmltable','htmlelements');

$dependants = $this->objDBDependants->getDependants($appnum);

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
        $table->addCell($data['firstname']);
        $table->addCell($data['relationship']);
        $table->addCell($data['dependantreason']);
        $table->addCell($data['category']);
        if($data['hasIncome'] == 0){
            $table->addCell('No');
        }else{
            $table->addCell('Yes');
        }
        $table->addCell($data['incometype']);
        $table->addCell($data['incomeamount']);
        $table->endRow();
    }
}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
