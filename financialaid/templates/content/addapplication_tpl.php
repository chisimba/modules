<?

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$details = "<center><h2>".$objLanguage->languagetext('mod_financialaid_addstudent','financialaid')."</h2></center>";

$stdNum = new textinput('stdnum');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
$table->addCell($stdNum->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();


$content = $details.$table->show();

$objForm = new form('applicationform');
$objForm->setAction($this->uri(array('action'=>'addapplication2')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$objForm->addRule('stdnum', $objLanguage->languagetext('mod_financialaid_stdnumrequired','financialaid'), 'required');
$objForm->addRule('stdnum', $objLanguage->languagetext('mod_financialaid_stdnumnumeric','financialaid'), 'numeric');
$objForm->addRule(array('name'=>'stdnum', 'lower'=>'6', 'upper'=>'7'), $objLanguage->languagetext('mod_financialaid_stdnumsetlength','financialaid'), 'rangelength');


echo $objForm->show();


?>
