<?
$appid = $this->getParam('appid');
$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);

$rep = array(
      'FIRSTNAME' => $stdinfo[0]->firstNames,
      'LASTNAME' => $stdinfo[0]->surname);

$details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_adddependanttitle','financialaid',$rep)."</h2></center>";
$details .="<center><div class='error'>".$objLanguage->languagetext('mod_financialaid_requiredfields','financialaid')."</div></center>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$appidfield = new textinput("appid", $appid,  "hidden", NULL);
$firstname = new textinput('firstname');
$relationship = new textinput('relationship');
$surname = new textinput('surname');
$idnumber = new textinput('idnumber');

$dependantReason = new textinput('dependantreason');
$hasIncome = new radio('hasIncome');
$hasIncome->addOption('1', $objLanguage->languagetext('word_yes'));
$hasIncome->addOption('0', $objLanguage->languagetext('word_no'));
$hasIncome->setBreakSpace('&nbsp;&nbsp;');

$isStudent = new radio('isstudent');
$isStudent->addOption('1', $objLanguage->languagetext('word_yes'));
$isStudent->addOption('0', $objLanguage->languagetext('word_no'));
$isStudent->setBreakSpace('&nbsp;&nbsp;');

$incomeType = new textinput('incomeType');
$incomeAmount = new textinput('incomeAmount');
$category = new textinput('category');

$incomePeriod = new dropdown('incomePeriod');
$incomePeriod->addOption('1', 'Annual');
$incomePeriod->addOption('12', 'Monthly');
$incomePeriod->addOption('26', 'Fortnightly');
$incomePeriod->addOption('52', 'Weekly');

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
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_firstname','financialaid').'<span class="error">&nbsp;*</span>'."</b>");
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('word_surname')."</b>");
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_idnumber','financialaid')."</b>");
$table->addCell($idnumber->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_howrelated','financialaid')."</b>");
$table->addCell($relationship->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_dependantreason','financialaid')."</b>");
$table->addCell($dependantReason->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_category','financialaid')."</b>");
$table->addCell($category->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_hasincome','financialaid')."</b>");
$table->addCell($hasIncome->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_incometype','financialaid')."</b>");
$table->addCell($incomeType->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_incomeamount','financialaid')."</b>");
$table->addCell($incomeAmount->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".$objLanguage->languagetext('mod_financialaid_incomepaymentperiod','financialaid')."</b>");
$table->addCell($incomePeriod->show());
$table->endRow();

$table->startRow();
$table->addCell("<b>".'Is this family memeber a tertiary level student?'."</b>");
$table->addCell($isStudent->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = $table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'savedependant')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

$objForm->addRule('firstname', $objLanguage->languagetext('mod_financialaid_firstnamerequired','financialaid'), 'required');

echo $details.$objForm->show();


?>
