<?
$appid = $this->getParam('appid');
$stdinfo = $this->objDBFinancialAidWS->getApplication($appid);

$rep = array(
      'FIRSTNAME' => $stdinfo[0]->firstNames,
      'LASTNAME' => $stdinfo[0]->surname);

$details = "<center><h2>".$objLanguage->code2Txt('mod_financialaid_addnextofkintitle','financialaid',$rep)."</h2></center>";
$details .="<center><div class='error'>".$objLanguage->languagetext('mod_financialaid_requiredfields','financialaid')."</div></center>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;

$appidfield = new textinput("appid", $appid,  "hidden", NULL);;
$idNum = new textinput('idnum');
$surname = new textinput('surname');
$firstname = new textinput('firstname');
$straddress = new textinput('straddress');
$suburb = new textinput('suburb');
$city = new textinput('city');
$postcode = new textinput('postcode');
$spouse = new textinput('spouse');
$occupation = new textinput('occupation');
$employerDetails = new textinput('employerdetails');
$employerTelNo = new textinput('employertelno');


$relationship = new dropdown('relationship');
$relationship->addOption('1',$objLanguage->languagetext('word_father').'&nbsp;&nbsp;');
$relationship->addOption('2',$objLanguage->languagetext('word_mother').'&nbsp;&nbsp;');
$relationship->addOption('3',$objLanguage->languagetext('word_guardian').'&nbsp;&nbsp;');
$relationship->addOption('4',$objLanguage->languagetext('word_widowed').'&nbsp;&nbsp;');

$maritalSts = new dropdown('maritalstatus');
$maritalSts->addOption('1',$objLanguage->languagetext('word_single').'&nbsp;&nbsp;');
$maritalSts->addOption('2',$objLanguage->languagetext('word_married').'&nbsp;&nbsp;');
$maritalSts->addOption('3',$objLanguage->languagetext('word_divorced').'&nbsp;&nbsp;');
$maritalSts->addOption('4',$objLanguage->languagetext('word_widowed').'&nbsp;&nbsp;');

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
$table->addCell($objLanguage->languagetext('mod_financialaid_idnumber','financialaid').'<span class="error">&nbsp;*</span>');
$table->addCell($idNum->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_surname','financialaid').'<span class="error">&nbsp;*</span>');
$table->addCell($surname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_firstnames','financialaid').'<span class="error">&nbsp;*</span>');
$table->addCell($firstname->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_relationship','financialaid'));
$table->addCell($relationship->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_homeaddress','financialaid'));
$table->addCell($straddress->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_suburb','financialaid'));
$table->addCell($suburb->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_city','financialaid'));
$table->addCell($city->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_pcode','financialaid'));
$table->addCell($postcode->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_mrtsts','financialaid'));
$table->addCell($maritalSts->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_spouse','financialaid'));
$table->addCell($spouse->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_occupation','financialaid'));
$table->addCell($occupation->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
$table->addCell($employerDetails->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_emptelno','financialaid'));
$table->addCell($employerTelNo->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();



$content = $table->show();

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'savenextofkin')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);
$objForm->addRule('idnum', $objLanguage->languagetext('mod_financialaid_idnumrequired','financialaid'), 'required');
$objForm->addRule('idnum', $objLanguage->languagetext('mod_financialaid_idnumnumeric','financialaid'), 'numeric');
$objForm->addRule(array('name'=>'idnum', 'length'=>'13'), $objLanguage->languagetext('mod_financialaid_idnumtoolong','financialaid'), 'maxlength');
$objForm->addRule('surname', $objLanguage->languagetext('mod_financialaid_surnamerequired','financialaid'), 'required');
$objForm->addRule('firstname', $objLanguage->languagetext('mod_financialaid_firstnamerequired','financialaid'), 'required');

echo $details.$objForm->show();

?>
