<?
if (!isset($appid)){
    $appid = $this->getParam('appid');
}
$this->objDBFinancialAidWS = & $this->getObject('dbfinancialaidws');

$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);

$stname = $stdinfo[0]->firstNames;
$stsname = $stdinfo[0]->surname;

$gender = array(
             '0'=>$objLanguage->languagetext('word_male'),
             '1'=>$objLanguage->languagetext('word_female'));
$yesno = array(
             '0'=>$objLanguage->languagetext('word_no'),
             '1'=>$objLanguage->languagetext('word_yes'));
             
$semester = array(
             '1',$objLanguage->languagetext('word_first'),
             '2',$objLanguage->languagetext('word_second'));


$maritalStatus = array('1'=>$objLanguage->languagetext('word_single'),
                  '2'=>$objLanguage->languagetext('word_married'),
                  '3'=>$objLanguage->languagetext('word_divorced'),
                  '4'=>$objLanguage->languagetext('word_widowed'));
$relationship = array('1'=>$objLanguage->languagetext('word_father'),
                  '2'=>$objLanguage->languagetext('word_mother'),
                  '3'=>$objLanguage->languagetext('word_guardian'),
                  '4'=>$objLanguage->languagetext('word_spouse'));
$rep = array(
      'FIRSTNAME' => $stname,
      'LASTNAME' => $stsname);
      
$details = "<h2>".$objLanguage->code2Txt('mod_financialaid_infotitle','financialaid',$rep)."</h2>";

$table =& $this->newObject('htmltable','htmlelements');

if(count($stdinfo) > 0){
    $table->startRow();
    $table->addCell($objLanguage->languagetext('word_year'));
    $table->addCell($stdinfo[0]->year);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('word_semester'));
    $table->addCell($semester[$stdinfo[0]->semester]);
    $table->endRow();

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
    $table->addCell($gender[$stdinfo[0]->gender]);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
    $table->addCell($yesno[$stdinfo[0]->saCitizen]);

	$table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
    $table->addCell($maritalStatus[$stdinfo[0]->maritalStatus]);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_courseofstudy','financialaid'));
    $table->addCell($stdinfo[0]->course);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_fulltime','financialaid'));
    $table->addCell($yesno[$stdinfo[0]->fulltime]);
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
    $table->addCell($yesno[$stdinfo[0]->supportingSelf]);
   	$table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_studyaddress','financialaid'));
    $table->addCell($stdinfo[0]->studyAddress1);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
    $table->addCell($stdinfo[0]->studyAddress2);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_city','financialaid'));
    $table->addCell($stdinfo[0]->studyAddress3);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
    $table->addCell($stdinfo[0]->studyPostcode);
    $table->endRow();

    $table->startRow();
    $table->addCell($objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
    $table->addCell($stdinfo[0]->homeAddress1);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
    $table->addCell($stdinfo[0]->homeAddress2);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_city','financialaid'));
    $table->addCell($stdinfo[0]->homeAddress3);
    $table->endRow();

    $table->startRow();
    $table->addCell("&nbsp;&nbsp;&nbsp;".$objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
    $table->addCell($stdinfo[0]->homePostcode);
    $table->endRow();
}

$content = "<center>".$details." ".$table->show(). "</center>";

echo $content;

?>
