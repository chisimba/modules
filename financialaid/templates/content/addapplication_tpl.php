<?

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addapp','financialaid')."</h2>";

$appid = "init" . "_" . rand(1000,9999) . "_" . time();
$appidfield = new textinput("appid", $appid,  "hidden", NULL);
$stdNum = new textinput('stdnum');
$idNum = new textinput('idNum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$maritalSts = new dropdown('maritalsts');
$maritalSts->addOption('Single', $objLanguage->languagetext('word_single'));
$maritalSts->addOption('Married', $objLanguage->languagetext('word_married'));
$maritalSts->addOption('Divorced', $objLanguage->languagetext('word_divorced'));
$maritalSts->addOption('Widowed', $objLanguage->languagetext('word_widowed'));

$gender = new radio('gender');
$gender->addOption('Male',$objLanguage->languagetext('word_male'));
$gender->addOption('Female',$objLanguage->languagetext('word_female'));
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
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'saveapplication')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$objForm->addRule('stdnum', 'Student number must not be blank', 'required');

echo $objForm->show();


?>
