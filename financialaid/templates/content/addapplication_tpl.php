<?

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addapp','financialaid')."</h2>";
$appNum = new textinput('appnum');
$stdNum = new textinput('stdnum');
$idNum = new textinput('idNum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$maritalSts = new textinput('maritalsts');

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

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_appnumber','financialaid'));
$table->addCell($appNum->show());
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

echo $objForm->show();


?>
