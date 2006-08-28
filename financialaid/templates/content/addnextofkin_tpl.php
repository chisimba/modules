<?
$right =& $this->getObject('blocksearchbox','studentenquiry');
$right = $right->show($this->getParam('module','studentenquiry'));

$left =& $this->getObject('financialaidleftblock');
$left = $left->show();

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$appnum = $this->getParam('appnum');

$appnumfield = new textinput("appnum", $appnum,  "hidden", NULL);;
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
$relationship->addOption('1','Father');
$relationship->addOption('2','Mother');
$relationship->addOption('3','Guardian');
$relationship->addOption('4','Spouse');

$maritalSts = new dropdown('maritalstatus');
$maritalSts->addOption('1','Married');
$maritalSts->addOption('2','Single');
$maritalSts->addOption('3','Divorced');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));

$table->startRow();

$table->addCell($appnumfield->show());
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
$table->addCell($objLanguage->languagetext('mod_financialaid_postcode','financialaid'));
$table->addCell($postcode->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_maritalsts','financialaid'));
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


// Create an instance of the css layout class
$cssLayout =& $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(3);
$cssLayout->setLeftColumnContent($left);
$cssLayout->setRightColumnContent($right);
$cssLayout->setMiddleColumnContent($objForm->show());

echo $cssLayout->show();


?>
