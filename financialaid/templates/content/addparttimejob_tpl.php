<?
$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addparttimejob','financialaid')."</h2>";

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
$table->addCell($objLanguage->languagetext('mod_financialaid_jobtitle','financialaid'));
$table->addCell($jobTitle->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_empdetails','financialaid'));
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


$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'saveparttimejob')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

echo $objForm->show();


?>
