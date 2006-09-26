<?

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$details = "<center><h2>".$objLanguage->languagetext('mod_financialaid_addappinfo','financialaid')."</h2></center>";

$stdNum = new textinput('stdnum');

$startyear = date("Y");

$year = new dropdown("year");
$year->addOption($startyear, $startyear.'&nbsp;&nbsp;&nbsp;');
$year->addOption($startyear + 1, ($startyear + 1).'&nbsp;&nbsp;&nbsp;');

$semester = new radio('semester');
$semester->addOption('1',$objLanguage->languagetext('word_first'));
$semester->addOption('2',$objLanguage->languagetext('word_second'));
$semester->setSelected('1');
$semester->setBreakSpace('&nbsp;&nbsp;');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_next'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
$table->addCell($stdNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('word_year'));
$table->addCell($year->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('word_semester'));
$table->addCell($semester->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();


$content = $details.$table->show();

$objForm = new form('applicationform');
$objForm->setAction($this->uri(array('action'=>'addappinfo')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$objForm->addRule('stdnum', $objLanguage->languagetext('mod_financialaid_stdnumrequired','financialaid'), 'required');
$objForm->addRule('stdnum', $objLanguage->languagetext('mod_financialaid_stdnumnumeric','financialaid'), 'numeric');
$objForm->addRule(array('name'=>'stdnum', 'lower'=>'6', 'upper'=>'7'), $objLanguage->languagetext('mod_financialaid_stdnumsetlength','financialaid'), 'rangelength');


echo $objForm->show();


?>
