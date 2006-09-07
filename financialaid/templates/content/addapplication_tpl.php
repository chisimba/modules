<?

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addapp','financialaid')."</h2>";

$appid = "init" . "_" . rand(1000,9999) . "_" . time();
$appidfield = new textinput("appid", $appid,  "hidden", NULL);
$stdNum = new textinput('stdnum');
$idNum = new textinput('idnum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$maritalSts = new dropdown('maritalsts');
$maritalSts->addOption('1', $objLanguage->languagetext('word_single'));
$maritalSts->addOption('2', $objLanguage->languagetext('word_married'));
$maritalSts->addOption('3', $objLanguage->languagetext('word_divorced'));
$maritalSts->addOption('4', $objLanguage->languagetext('word_widowed'));

//$year = new dropdown('year');
$startyear = date("Y");
//for($i = 0; $i < 16; $i ++){
//	$year->addOption($start,$start);
//	$start--;
//}

$year = new textinput("year", $start,  "hidden", NULL);


$semester = new radio('semester');
for($i = 1; $i <= 2; $i ++){
	$semester->addOption($i,$i);
}
$semester->setSelected('1');
$semester->setBreakSpace('  ');


$gender = new radio('gender');
$gender->addOption('0',$objLanguage->languagetext('word_male'));
$gender->addOption('1',$objLanguage->languagetext('word_female'));
$gender->setBreakSpace('  ');

$SACitizen = new radio('saCitizen');
$SACitizen->addOption('1',$objLanguage->languagetext('word_yes'));
$SACitizen->addOption('0',$objLanguage->languagetext('word_no'));
$SACitizen->setSelected('1');
$SACitizen->setBreakSpace('  ');

$supportingSelf = new radio('supportingself');
$supportingSelf->addOption('1',$objLanguage->languagetext('word_yes'));
$supportingSelf->addOption('0',$objLanguage->languagetext('word_no'));
$supportingSelf->setSelected('1');
$supportingSelf->setBreakSpace('  ');

$course = new textinput('course');
$majors = new textinput('majors');
$hometelno = new textinput('hometelno');
$cellno = new textinput('cellno');

$fulltime = new radio('fulltime');
$fulltime->addOption('1',$objLanguage->languagetext('word_yes'));
$fulltime->addOption('0',$objLanguage->languagetext('word_no'));
$fulltime->setSelected('1');
$fulltime->setBreakSpace('  ');

$studyAddress1 = new textinput('studyaddress1');
$studyAddress2 = new textinput('studyaddress2');
$studyAddress3 = new textinput('studyaddress3');
$studyPostcode = new textinput('studypostcode');
$homeAddress1 = new textinput('homeaddress1');
$homeAddress2 = new textinput('homeaddress2');
$homeAddress3 = new textinput('homeaddress3');
$homePostcode = new textinput('homepostcode');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($appidfield->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('word_year'));
//$table->addCell($year->show());
$table->addCell($startyear);
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('word_semester'));
$table->addCell($semester->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
$table->addCell($stdNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid'));
$table->addCell($idNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid'));
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid'));
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_gender','financialaid'));
$table->addCell($gender->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_sacitizen','financialaid'));
$table->addCell($SACitizen->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
$table->addCell($maritalSts->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_courseofstudy','financialaid'));
$table->addCell($course->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_fulltime','financialaid'));
$table->addCell($fulltime->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_majorsubjects','financialaid'));
$table->addCell($majors->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_hometelno','financialaid'));
$table->addCell($hometelno->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_cellno','financialaid'));
$table->addCell($cellno->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_supportingself','financialaid'));
$table->addCell($supportingSelf->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_studyaddress','financialaid'));
$table->addCell($studyAddress1->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
$table->addCell($studyAddress2->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'));
$table->addCell($studyAddress3->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
$table->addCell($studyPostcode->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
$table->addCell($homeAddress1->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
$table->addCell($homeAddress2->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'));
$table->addCell($homeAddress3->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
$table->addCell($homePostcode->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'saveapplication')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

//$objForm->addRule('stdnum', 'Please enter a student number', 'required');
//$objForm->addRule('surname', 'Please enter a surname', 'required');
//$objForm->addRule('idnum', 'Please enter an ID number', 'required');

echo $objForm->show();


?>
