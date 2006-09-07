<?
$stdnum = $this->getParam('studentNumber');
$surname = $this->getParam('surname');
$idnumber = $this->getParam('idNumber');

$stname = $stdinfo[0]->FSTNAM;
$stsname = $stdinfo[0]->SURNAM;
$this->objStudyFeeCalc =& $this->getObject('studyfeecalc','financialaid');
$this->objDbFinAid =& $this->getObject('dbfinaid');


$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);

$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_accounttitle','financialaid',$rep)."</h2>";

$idnumber = $stdinfo[0]->IDN;
$stdnum = $stdinfo[0]->STDNUM;

$year = $this->getParam('year');
if(is_null($year)){
    $year = date('Y');
}



$accountTotal = 0;
$oddEven = 'odd';

$registrationfee = 0;
$tuitionfee = 0;
$hostelfee = 0;

$registrationfee = $this->objStudyFeeCalc->getRegistrationFee($stdnum);
$tuitionfee = $this->objStudyFeeCalc->getTuitionFee($stdnum);
$hostelfee = $this->objStudyFeeCalc->getHostelFee($stdnum);

$details .= "<br />".$objLanguage->languagetext('word_year').": ".$year."<br /><br />";
$tableAccount =& $this->newObject('htmltable','htmlelements');
$tableAccount->startRow();
$tableAccount->addCell('Registration Fee');
$tableAccount->addCell($registrationfee);
$tableAccount->endRow();
$tableAccount->startRow();
$tableAccount->addCell('Tuition Fee');
$tableAccount->addCell($tuitionfee);
$tableAccount->endRow();
$tableAccount->startRow();
$tableAccount->addCell('Hostel Fee');
$tableAccount->addCell($hostelfee);
$tableAccount->endRow();

$content = "<center>".$details." " . $tableAccount->show()."</center>";

echo $content;
?>
