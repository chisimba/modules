<?

$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addnextofkin','financialaid')."</h2>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$appid = $this->getParam('appid');

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
$relationship->addOption('1',$objLanguage->languagetext('word_father'));
$relationship->addOption('2',$objLanguage->languagetext('word_mother'));
$relationship->addOption('3',$objLanguage->languagetext('word_guardian'));
$relationship->addOption('4',$objLanguage->languagetext('word_widowed'));

$maritalSts = new dropdown('maritalstatus');
$maritalSts->addOption('1',$objLanguage->languagetext('word_single'));
$maritalSts->addOption('2',$objLanguage->languagetext('word_married'));
$maritalSts->addOption('3',$objLanguage->languagetext('word_divorced'));
$maritalSts->addOption('4',$objLanguage->languagetext('word_widowed'));

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



$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'savenextofkin')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

echo $objForm->show();

?>
