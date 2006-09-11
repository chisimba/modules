<?

$details = "<h2>".$objLanguage->languagetext('mod_financialaid_addstudentfamily','financialaid')."</h2>";

$table =& $this->newObject('htmltable','htmlelements');
$table->cellspacing = 2;
$table->cellpadding = 2;
$appid = $this->getParam('appid');

$appidfield = new textinput("appid", $appid,  "hidden", NULL);
if (isset($fname)){
    $firstname = new textinput('firstname', $fname, "hidden",NULL);
}else{
    $firstname = new textinput('firstname');
}
$institution = new textinput('institution');
$course = new textinput('course');
$year = new textinput('year');
$stdnum = new textinput('stdnum');

$addbut= new button('add');
$addbut->setToSubmit();
$addbut->setValue($objLanguage->languagetext('word_add'));

$cancelbut= new button('cancel');
$cancelbut->setToSubmit();
$cancelbut->setValue($objLanguage->languagetext('word_cancel'));


$table->startRow();
$table->addCell($appidfield->show());
$table->endRow();

if(isset($fname)){
    $table->startRow();
    $table->addCell($firstname->show());
    $table->endRow();
}

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_firstname','financialaid'));
if(isset($fname)){
    $table->addCell($fname);
}else{
    $table->addCell($firstname->show());
}
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_institution','financialaid'));
$table->addCell($institution->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('word_course'));
$table->addCell($course->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_yearofstudy','financialaid'));
$table->addCell($year->show());
$table->endRow();

$table->startRow();
$table->addCell($objLanguage->languagetext('mod_financialaid_stdnum2','financialaid'));
$table->addCell($stdnum->show());
$table->endRow();

$table->startRow();
$table->addCell($addbut->show());
$table->addCell($cancelbut->show());
$table->endRow();


$content = "<center>".$details."  ".$table->show()."</center>";

$objForm = new form('theform');
$objForm->setAction($this->uri(array('action'=>'savestudentfamily')));
$objForm->setDisplayType(2);

$objForm->addToForm($content);

echo $objForm->show();


?>
