<?
$appid = $this->getParam('appid');
$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);

$rep = array(
      'FIRSTNAME' => $stdinfo[0]->firstNames,
      'LASTNAME' => $stdinfo[0]->surname);

$details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_addparttimejobtitle','financialaid',$rep)."</h2></center>";
$details .="<center><div class='error'>".$objLanguage->languagetext('mod_financialaid_requiredfields','financialaid')."</div></center>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$appid = $this->getParam('appid');

$appidfield = new textinput("appid", $appid,  "hidden", NULL);
$jobTitle = new textinput('jobtitle');
$employersDetails = new textinput('employersdetails');
$employersTelNo = new textinput('employerstelno');

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
$table->addCell($objLanguage->languagetext('mod_financialaid_jobtitle','financialaid').'<span class="error">&nbsp;*</span>');
$table->addCell($jobTitle->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid').'<span class="error">&nbsp;*</span>');
$table->addCell($employersDetails->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
$table->addCell($employersTelNo->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();


$content = $table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'saveparttimejob')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);
$objForm->addRule('jobtitle', $objLanguage->languagetext('mod_financialaid_jobtitlerequired','financialaid'), 'required');
$objForm->addRule('employersdetails', $objLanguage->languagetext('mod_financialaid_employerrequired','financialaid'), 'required');

echo $details.$objForm->show();


?>
